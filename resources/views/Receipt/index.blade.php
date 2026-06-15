<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="font-display text-2xl font-bold" style="color: var(--color-ink);">Mes reçus</h1>
        <a href="{{ route('Receipt.create') }}" class="btn-ink">
            + Nouveau reçu
        </a>
    </div>

    @if (count($categories))
        <div class="flex items-center gap-2 mb-6 flex-wrap">
            <a href="{{ route('Receipt.index') }}"
               class="stamp-chip {{ !$activeFilter ? 'active' : '' }}">
                Toutes
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('Receipt.index', ['categorie' => $cat->value]) }}"
                   class="stamp-chip {{ $activeFilter === $cat->value ? 'active' : '' }}">
                    {{ $cat->label() }}
                </a>
            @endforeach
        </div>
    @endif

    @if ($receipt->isEmpty())
        <div class="ticket max-w-md mx-auto text-center" style="padding: 3rem;">
            <svg class="w-12 h-12 mx-auto mb-4" style="color: var(--color-line);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
            <p style="color: var(--color-ink-soft); font-size: 0.875rem;">Aucun reçu pour le moment.</p>
            <a href="{{ route('Receipt.create') }}" class="btn-ghost mt-4 inline-block">
                Soumettre votre premier reçu
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($receipt as $recu)
                <div class="ticket">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <span class="font-num text-xs font-semibold" style="color: var(--color-ink-soft);">
                                #{{ str_pad($recu->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="font-num text-xs ml-2" style="color: var(--color-ink-soft);">
                                {{ $recu->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        @php
                            $stampClass = match($recu->status?->value) {
                                'pending' => 'stamp-amber',
                                'processed' => 'stamp-green',
                                'failed' => 'stamp-red',
                                default => '',
                            };
                        @endphp
                        <span class="stamp {{ $stampClass }}">
                            {{ $recu->status?->label() }}
                        </span>
                    </div>

                    <div class="mb-3" style="color: var(--color-ink-soft); font-size: 0.875rem;">
                        @if ($recu->status?->value === 'processed')
                            {{ $recu->expenses->count() }} article(s)
                        @else
                            En attente de traitement...
                        @endif
                    </div>

                    <hr class="dashed-divider">

                    <div class="flex items-center gap-4">
                        <a href="{{ route('Receipt.show', $recu) }}" class="btn-ghost text-xs">
                            Voir le détail →
                        </a>
                        @if ($recu->status?->value === 'processed')
                            <a href="{{ route('Receipt.edit', $recu) }}" class="btn-ghost text-xs">
                                Modifier
                            </a>
                        @endif
                        <form action="{{ route('Receipt.destroy', $recu) }}" method="POST" class="ml-auto"
                            onsubmit="return confirm('Supprimer ce reçu ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-ghost text-xs" style="color: var(--color-red); border-color: var(--color-red);">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
