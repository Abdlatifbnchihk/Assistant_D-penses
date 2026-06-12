<?php

namespace App\Services;

use App\Enums\CategorieExpensesEnum;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ExtractionService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.openai.key', env('OPENAI_API_KEY'));
    }

    public function extractFromText(string $text): array
    {
        if (empty($this->apiKey) || $this->apiKey === 'your-openai-api-key-here') {
            throw new RuntimeException('OPENAI_API_KEY is not configured.');
        }

        $systemPrompt = <<<'PROMPT'
Tu es un assistant spécialisé dans l'extraction de données de reçus.
Analyse le texte du reçu fourni et extrais toutes les dépenses.

Tu dois retourner un JSON valide avec cette structure exacte :
{
  "expenses": [
    {
      "libelle": "nom du produit ou service",
      "quantite": 1,
      "prix_unitaire": 0.00,
      "categorie": "alimentaire"
    }
  ]
}

Règles :
- "libelle" : description claire du produit/service
- "quantite" : nombre entier (défaut 1 si non précisé)
- "prix_unitaire" : prix pour une unité (decimal avec 2 chiffres après la virgule)
- "categorie" : UNE SEULE valeur parmi : alimentaire, boissons, hygiene, entretien, autre
- Si le prix total pour une quantité est indiqué mais pas le prix unitaire, divise le total par la quantité
- Si une catégorie n'est pas claire, utilise "autre"
- Retourne UNIQUEMENT le JSON, pas de texte avant ou après
PROMPT;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(60)->post($this->baseUrl . '/chat/completions', [
            'model' => 'gpt-4o-mini',
            'response_format' => ['type' => 'json_object'],
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => "Voici le texte du reçu :\n\n{$text}"],
            ],
            'temperature' => 0.1,
        ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'OpenAI API error: ' . $response->status() . ' - ' . $response->body()
            );
        }

        $data = $response->json();

        if (!isset($data['choices'][0]['message']['content'])) {
            throw new RuntimeException('Invalid API response structure.');
        }

        $content = $data['choices'][0]['message']['content'];
        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Failed to parse AI response as JSON: ' . json_last_error_msg());
        }

        if (!isset($decoded['expenses']) || !is_array($decoded['expenses'])) {
            throw new RuntimeException('AI response missing "expenses" array.');
        }

        return $this->validateExpenses($decoded['expenses']);
    }

    private function validateExpenses(array $expenses): array
    {
        $validCategories = array_column(CategorieExpensesEnum::cases(), 'value');
        $validated = [];

        foreach ($expenses as $expense) {
            $libelle = trim($expense['libelle'] ?? '');
            if ($libelle === '') {
                continue;
            }

            $categorie = strtolower($expense['categorie'] ?? 'autre');
            $categorie = in_array($categorie, $validCategories) ? $categorie : 'autre';

            $validated[] = [
                'libelle' => $libelle,
                'quantite' => max(1, (int) ($expense['quantite'] ?? 1)),
                'prix_unitaire' => round((float) ($expense['prix_unitaire'] ?? 0), 2),
                'categorie' => $categorie,
            ];
        }

        return $validated;
    }
}
