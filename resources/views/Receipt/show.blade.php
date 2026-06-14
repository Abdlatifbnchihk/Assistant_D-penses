<x-app-layout>
    @php
        $colors = [
            'pending'   => 'bg-amber-100 text-amber-800',
            'processed' => 'bg-green-100 text-green-800',
            'failed'    => 'bg-red-100 text-red-800',
        ];
        $catColors = [
            'alimentaire' => 'bg-green-100 text-green-800',
            'boissons'    => 'bg-blue-100 text-blue-800',
            'hygiene'     => 'bg-pink-100 text-pink-800',
            'entretien'   => 'bg-amber-100 text-amber-800',
            'autre'       => 'bg-gray-100 text-gray-800',
        ];
    @endphp

    {{-- {{ dd($receipt->expenses) }} --}}

    <div class="flex items-center gap-3 mb-6">
        <h1 class="text-xl font-semibold text-gray-900">
            Reçu du {{ $receipt->created_at->format('d/m/Y H:i') }}
        </h1>
        <span class="text-xs font-medium px-2 py-1 rounded-full {{ $colors[$receipt->status->value] }}">
            {{ $receipt->status->label() }}
        </span>
    </div>

    @if ($receipt->status->value === 'failed')
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
            L'extraction a échoué. Le service IA était injoignable ou a renvoyé une réponse invalide.
        </div>
    @endif

    @if ($receipt->status->value === 'pending')
        <div class="mb-4 rounded-md bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-800">
            Traitement en cours... Rafraîchissez la page dans quelques secondes.
        </div>
    @endif

    <div class="mb-6">
        <h2 class="text-sm font-medium text-gray-700 mb-2">Texte source</h2>
        <pre class="bg-white border border-gray-200 rounded-md p-4 text-xs font-mono text-gray-600 whitespace-pre-wrap">{{ $receipt->source_text }}</pre>
    </div>

    <div>
        <h2 class="text-sm font-medium text-gray-700 mb-2">Dépenses extraites</h2>

        @if ($receipt->expenses->isEmpty())
            <div class="text-center py-10 bg-white rounded-lg border border-gray-200">
                <p class="text-gray-500 text-sm">Aucune dépense extraite pour le moment.</p>
            </div>
        @else
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                        <tr>
                            <th class="text-left px-4 py-3">Article</th>
                            <th class="text-left px-4 py-3">Quantité</th>
                            <th class="text-left px-4 py-3">Prix unitaire</th>
                            <th class="text-left px-4 py-3">Catégorie</th>
                            <th class="text-right px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($receipt->expenses as $expense)
                            <tr>
                                <td class="px-4 py-3 text-gray-700">{{ $expense->libelle }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $expense->quantite }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ number_format($expense->prix_unitaire, 2) }} MAD</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-medium px-2 py-1 rounded-full {{ $catColors[$expense->categorie->value] }}">
                                        {{ $expense->categorie->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-gray-900">
                                    {{ number_format($expense->prix_total, 2) }} MAD
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('Receipt.index') }}" class="text-sm text-indigo-600 hover:underline">
            ← Retour aux reçus
        </a>
    </div>
</x-app-layout>