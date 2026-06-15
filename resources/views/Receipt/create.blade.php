<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="ticket">
            <h1 class="font-display text-2xl font-bold mb-1" style="color: var(--color-ink);">
                Nouveau reçu fournisseur
            </h1>
            <p class="text-sm mb-6" style="color: var(--color-ink-soft);">
                Collez le texte brut du reçu. L'IA extraira automatiquement
                les dépenses et les classera par catégorie.
            </p>

            <form method="POST" action="{{ route('Receipt.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="source_text" class="block text-sm font-semibold mb-2" style="color: var(--color-ink);">
                        Texte du reçu <span style="color: var(--color-red);">*</span>
                    </label>

                    <textarea id="source_text" name="source_text" rows="12" required
                        class="lined-paper w-full"
                        placeholder="2 Coca Cola 12 MAD

1 Huile Lesieur 35 MAD

3 Savon Dove 8 MAD">{{ old('source_text') }}</textarea>

                    <p class="mt-2 text-xs" style="color: var(--color-ink-soft); font-family: var(--font-mono);">
                        Min. 10 caractères — Max. 5000 caractères
                    </p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-ink">
                        Lancer l'extraction
                    </button>
                </div>
            </form>
        </div>

        <div class="banner-info mt-6 flex items-start gap-2">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <strong style="font-family: var(--font-mono); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Traitement automatique</strong>
                <p class="text-sm mt-1" style="color: var(--color-teal);">
                    Après l'envoi, le reçu sera traité en arrière-plan.
                    Son statut passera de <strong>En attente</strong> à <strong>Traité</strong> ou <strong>Échoué</strong>.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
