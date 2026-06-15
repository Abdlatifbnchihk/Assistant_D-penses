<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="ticket">
            <h1 class="font-display text-2xl font-bold mb-1" style="color: var(--color-ink);">
                Modifier le reçu
            </h1>
            <p class="text-sm mb-6" style="color: var(--color-ink-soft);">
                Reçu du {{ $receipt->created_at->format('d/m/Y H:i') }}
            </p>

            <form method="POST" action="{{ route('Receipt.update', $receipt) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="source_text" class="block text-sm font-semibold mb-2" style="color: var(--color-ink);">
                        Texte du reçu <span style="color: var(--color-red);">*</span>
                    </label>
                    <textarea id="source_text" name="source_text" rows="12" required
                        class="lined-paper w-full">{{ old('source_text', $receipt->source_text) }}</textarea>
                    <p class="mt-2 text-xs" style="color: var(--color-ink-soft); font-family: var(--font-mono);">
                        Min. 10 caractères — Max. 5000 caractères
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('Receipt.show', $receipt) }}" class="btn-ghost">
                        Annuler
                    </a>
                    <button type="submit" class="btn-ink">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
