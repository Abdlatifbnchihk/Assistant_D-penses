<x-app-layout>
@php
    $stampColors = [
        'pending'   => 'stamp-amber',
        'processed' => 'stamp-green',
        'failed'    => 'stamp-red',
    ];
    $catStampColors = [
        'alimentaire' => 'stamp-green',
        'boissons'    => 'stamp-teal',
        'hygiene'     => 'stamp-amber',
        'entretien'   => 'stamp-amber',
        'autre'       => '',
    ];
@endphp

<!-- Ticket Header -->
<div class="ticket mb-6">
    <div class="text-center mb-4">
        <div class="font-display text-lg font-bold" style="color: var(--color-ink);">Assistant Dépenses</div>
        <div class="font-num text-xs" style="color: var(--color-ink-soft);">
            Reçu #{{ str_pad($receipt->id, 4, '0', STR_PAD_LEFT) }}
        </div>
        <div class="font-num text-xs" style="color: var(--color-ink-soft);">
            {{ $receipt->created_at->format('d/m/Y H:i') }}
        </div>
    </div>

    <div class="flex items-center justify-center gap-3">
        <span class="stamp {{ $stampColors[$receipt->status->value] ?? '' }}">
            {{ $receipt->status->label() }}
        </span>
        @if ($receipt->status->value === 'processed')
            <a href="{{ route('Receipt.edit', $receipt) }}" class="btn-ghost text-xs">
                Modifier
            </a>
        @endif
    </div>
</div>

@if ($receipt->status->value === 'failed')
    <div class="banner-red flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        L'extraction a échoué. Le service IA était injoignable ou a renvoyé une réponse invalide.
    </div>
@endif

@if ($receipt->status->value === 'pending')
    <div class="banner-amber flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
        </svg>
        Traitement en cours... Rafraîchissez la page dans quelques secondes.
    </div>
@endif

<!-- Source Text -->
<div class="ticket mb-6">
    <h2 class="font-display text-sm font-semibold mb-3" style="color: var(--color-ink);">Texte source</h2>
    <div class="lined-paper">{{ $receipt->source_text }}</div>
</div>

<!-- Expenses -->
<div class="ticket">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-display text-sm font-semibold" style="color: var(--color-ink);">Dépenses extraites</h2>

        @if ($receipt->status->value === 'processed' && count($categories))
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('Receipt.show', $receipt) }}"
                   class="stamp-chip {{ !$activeFilter ? 'active' : '' }}">
                    Toutes
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('Receipt.show', ['recu' => $receipt, 'categorie' => $cat->value]) }}"
                       class="stamp-chip {{ $activeFilter === $cat->value ? 'active' : '' }}">
                        {{ $cat->label() }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    @if ($expenses->isEmpty())
        <div class="text-center py-8" style="color: var(--color-ink-soft);">
            <p class="text-sm">Aucune dépense pour le moment.</p>
        </div>
    @else
        <table class="w-full" style="border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--color-line);">
                    <th class="text-left py-2 font-num text-xs font-semibold uppercase" style="color: var(--color-ink-soft); letter-spacing: 0.05em;">Article</th>
                    <th class="text-left py-2 font-num text-xs font-semibold uppercase" style="color: var(--color-ink-soft); letter-spacing: 0.05em;">Qté</th>
                    <th class="text-left py-2 font-num text-xs font-semibold uppercase" style="color: var(--color-ink-soft); letter-spacing: 0.05em;">Prix unit.</th>
                    <th class="text-left py-2 font-num text-xs font-semibold uppercase" style="color: var(--color-ink-soft); letter-spacing: 0.05em;">Catégorie</th>
                    <th class="text-right py-2 font-num text-xs font-semibold uppercase" style="color: var(--color-ink-soft); letter-spacing: 0.05em;">Total</th>
                    @if ($receipt->status->value === 'processed')
                        <th class="text-right py-2 font-num text-xs font-semibold uppercase" style="color: var(--color-ink-soft); letter-spacing: 0.05em;"></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr x-data="{ editing: false }" style="border-bottom: 1px dashed var(--color-line);">
                        {{-- Display mode --}}
                        <template x-if="!editing">
                            <td class="py-3 text-sm" style="color: var(--color-ink);">{{ $expense->libelle }}</td>
                        </template>
                        <template x-if="!editing">
                            <td class="py-3 font-num text-sm" style="color: var(--color-ink);">{{ $expense->quantite }}</td>
                        </template>
                        <template x-if="!editing">
                            <td class="py-3 font-num text-sm" style="color: var(--color-ink);">{{ number_format($expense->prix_unitaire, 2) }}</td>
                        </template>
                        <template x-if="!editing">
                            <td class="py-3">
                                <span class="stamp {{ $catStampColors[$expense->categorie->value] ?? '' }}" style="font-size: 0.6rem; padding: 0.1rem 0.4rem;">
                                    {{ $expense->categorie->label() }}
                                </span>
                            </td>
                        </template>
                        <template x-if="!editing">
                            <td class="py-3 text-right font-num text-sm font-semibold" style="color: var(--color-ink);">{{ number_format($expense->prix_total, 2) }}</td>
                        </template>
                        @if ($receipt->status->value === 'processed')
                            <template x-if="!editing">
                                <td class="py-3 text-right">
                                    <button @click="editing = true" class="btn-ghost text-xs" style="padding: 0.25rem 0.5rem;">
                                        Modifier
                                    </button>
                                    <form action="{{ route('Expense.destroy', $expense) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Supprimer cette dépense ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost text-xs" style="padding: 0.25rem 0.5rem; color: var(--color-red); border-color: var(--color-red);">
                                            Supprimer
                                        </button>
                                    </form>
                                </td>
                            </template>
                        @endif

                        {{-- Edit mode --}}
                        <template x-if="editing">
                            <td class="py-2" colspan="{{ $receipt->status->value === 'processed' ? 6 : 5 }}">
                                <form method="POST" action="{{ route('Expense.update', $expense) }}" class="flex items-center gap-2 flex-wrap">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="libelle" value="{{ $expense->libelle }}" required
                                        class="flex-1 min-w-[150px]" style="font-size: 0.8rem;">
                                    <input type="number" name="quantite" value="{{ $expense->quantite }}" min="1" required
                                        class="w-20" style="font-size: 0.8rem;">
                                    <input type="number" name="prix_unitaire" value="{{ $expense->prix_unitaire }}" step="0.01" min="0.01" required
                                        class="w-24" style="font-size: 0.8rem;">
                                    <select name="categorie" required style="font-size: 0.8rem;">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->value }}" {{ $expense->categorie->value === $cat->value ? 'selected' : '' }}>
                                                {{ $cat->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn-ink" style="padding: 0.35rem 0.75rem; font-size: 0.7rem;">
                                        Sauver
                                    </button>
                                    <button type="button" @click="editing = false" class="btn-ghost" style="padding: 0.35rem 0.75rem; font-size: 0.7rem;">
                                        Annuler
                                    </button>
                                </form>
                            </td>
                        </template>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="border-top: 2px solid var(--color-line);">
                    <td colspan="4" class="py-3 font-display font-bold" style="color: var(--color-ink);">Total</td>
                    <td class="py-3 text-right font-num font-bold text-sm" style="color: var(--color-ink);">
                        {{ number_format($expenses->sum('prix_total'), 2) }}
                    </td>
                    @if ($receipt->status->value === 'processed')
                        <td></td>
                    @endif
                </tr>
            </tfoot>
        </table>
    @endif

    @if ($receipt->status->value === 'processed')
        <hr class="dashed-divider">

        <div>
            <h3 class="font-display text-sm font-semibold mb-3" style="color: var(--color-ink);">Ajouter une dépense</h3>
            <form method="POST" action="{{ route('Expense.store', $receipt) }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                @csrf
                <input type="text" name="libelle" placeholder="Article" required style="font-size: 0.8rem;">
                <input type="number" name="quantite" placeholder="Qté" value="1" min="1" required style="font-size: 0.8rem;">
                <input type="number" name="prix_unitaire" placeholder="Prix unitaire" step="0.01" min="0.01" required style="font-size: 0.8rem;">
                <select name="categorie" required style="font-size: 0.8rem;">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                    @endforeach
                </select>
                <div class="sm:col-span-4 flex justify-end">
                    <button type="submit" class="btn-ink">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>

<div class="mt-6">
    <a href="{{ route('Receipt.index') }}" class="btn-ghost">
        ← Retour aux reçus
    </a>
</div>
</x-app-layout>
