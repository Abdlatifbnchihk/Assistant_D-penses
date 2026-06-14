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

    <div class="flex items-center gap-3 mb-6">
        <h1 class="text-xl font-semibold text-gray-900">
            Reçu du {{ $receipt->created_at->format('d/m/Y H:i') }}
        </h1>
        <span class="text-xs font-medium px-2 py-1 rounded-full {{ $colors[$receipt->status->value] }}">
            {{ $receipt->status->label() }}
        </span>
        @if ($receipt->status->value === 'processed')
            <a href="{{ route('Receipt.edit', $receipt) }}"
                class="text-sm text-indigo-600 hover:underline ml-2">
                Modifier
            </a>
        @endif
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
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-sm font-medium text-gray-700">Dépenses extraites</h2>

            @if ($receipt->status->value === 'processed' && count($categories))
                <form method="GET" action="{{ route('Receipt.show', $receipt) }}" class="flex items-center gap-2">
                    <label for="categorie-filter" class="text-xs text-gray-500">Filtrer :</label>
                    <select name="categorie" id="categorie-filter"
                        class="text-xs border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        onchange="this.form.submit()">
                        <option value="">Toutes</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->value }}" {{ $activeFilter === $cat->value ? 'selected' : '' }}>
                                {{ $cat->label() }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>

        @if ($expenses->isEmpty())
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
                            @if ($receipt->status->value === 'processed')
                                <th class="text-right px-4 py-3">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($expenses as $expense)
                            <tr x-data="{ editing: false }">
                                {{-- Display mode --}}
                                <template x-if="!editing">
                                    <td class="px-4 py-3 text-gray-700" x-text="'{{ addslashes($expense->libelle) }}'"></td>
                                </template>
                                <template x-if="!editing">
                                    <td class="px-4 py-3 text-gray-700" x-text="'{{ $expense->quantite }}'"></td>
                                </template>
                                <template x-if="!editing">
                                    <td class="px-4 py-3 text-gray-700" x-text="'{{ number_format($expense->prix_unitaire, 2) }} MAD'"></td>
                                </template>
                                <template x-if="!editing">
                                    <td class="px-4 py-3">
                                        <span class="text-xs font-medium px-2 py-1 rounded-full {{ $catColors[$expense->categorie->value] }}">
                                            {{ $expense->categorie->label() }}
                                        </span>
                                    </td>
                                </template>
                                <template x-if="!editing">
                                    <td class="px-4 py-3 text-right font-medium text-gray-900" x-text="'{{ number_format($expense->prix_total, 2) }} MAD'"></td>
                                </template>
                                @if ($receipt->status->value === 'processed')
                                    <template x-if="!editing">
                                        <td class="px-4 py-3 text-right space-x-2">
                                            <button @click="editing = true" class="text-indigo-600 hover:underline text-xs">
                                                Modifier
                                            </button>
                                            <form action="{{ route('Expense.destroy', $expense) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Supprimer cette dépense ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline text-xs">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </template>
                                @endif

                                {{-- Edit mode --}}
                                <template x-if="editing">
                                    <td class="px-2 py-2" colspan="{{ $receipt->status->value === 'processed' ? 6 : 5 }}">
                                        <form method="POST" action="{{ route('Expense.update', $expense) }}" class="flex items-center gap-2 flex-wrap">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="libelle" value="{{ $expense->libelle }}" required
                                                class="flex-1 min-w-[150px] rounded-md border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            <input type="number" name="quantite" value="{{ $expense->quantite }}" min="1" required
                                                class="w-20 rounded-md border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            <input type="number" name="prix_unitaire" value="{{ $expense->prix_unitaire }}" step="0.01" min="0.01" required
                                                class="w-28 rounded-md border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            <select name="categorie" required
                                                class="rounded-md border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->value }}" {{ $expense->categorie->value === $cat->value ? 'selected' : '' }}>
                                                        {{ $cat->label() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-md hover:bg-green-700">
                                                Sauver
                                            </button>
                                            <button type="button" @click="editing = false"
                                                class="px-3 py-1.5 border border-gray-300 text-gray-700 text-xs font-medium rounded-md hover:bg-gray-50">
                                                Annuler
                                            </button>
                                        </form>
                                    </td>
                                </template>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if ($receipt->status->value === 'processed')
            <div class="mt-4 bg-white rounded-lg border border-gray-200 p-4">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Ajouter une dépense</h3>
                <form method="POST" action="{{ route('Expense.store', $receipt) }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    @csrf
                    <input type="text" name="libelle" placeholder="Article" required
                        class="rounded-lg border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="number" name="quantite" placeholder="Qté" value="1" min="1" required
                        class="rounded-lg border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="number" name="prix_unitaire" placeholder="Prix unitaire" step="0.01" min="0.01" required
                        class="rounded-lg border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <select name="categorie" required
                        class="rounded-lg border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                        @endforeach
                    </select>
                    <div class="sm:col-span-4 flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('Receipt.index') }}" class="text-sm text-indigo-600 hover:underline">
            ← Retour aux reçus
        </a>
    </div>
</x-app-layout>
