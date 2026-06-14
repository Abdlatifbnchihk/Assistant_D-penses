<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white rounded-xl shadow border border-gray-200">
            <div class="border-b px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-900">
                    Modifier le reçu
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Modifiez le texte brut du reçu du {{ $receipt->created_at->format('d/m/Y H:i') }}.
                </p>
            </div>

            <form method="POST" action="{{ route('Receipt.update', $receipt) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <ul class="text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label for="source_text" class="block text-sm font-semibold text-gray-700 mb-2">
                        Texte du reçu
                        <span class="text-red-500">*</span>
                    </label>
                    <textarea id="source_text" name="source_text" rows="12" required
                        class="w-full rounded-lg border-gray-300 shadow-sm font-mono text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('source_text', $receipt->source_text) }}</textarea>
                    <p class="mt-2 text-xs text-gray-500">
                        Minimum 10 caractères, maximum 5000 caractères.
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('Receipt.show', $receipt) }}"
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
