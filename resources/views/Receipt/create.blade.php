<div class="max-w-4xl mx-auto py-8">

    <div class="bg-white rounded-xl shadow border border-gray-200">

        <!-- Header -->
        <div class="border-b px-6 py-4">
            <h1 class="text-2xl font-bold text-gray-900">
                Nouveau reçu fournisseur
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Collez le texte brut du reçu. L'IA extraira automatiquement
                les dépenses et les classera par catégorie.
            </p>
        </div>

        <!-- Body -->
        <form method="POST" action="{{ route('Receipt.store') }}" class="p-6 space-y-6">
            @csrf

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Text Area -->
            <div>
                <label for="source_text" class="block text-sm font-semibold text-gray-700 mb-2">

                    Texte du reçu
                    <span class="text-red-500">*</span>
                </label>

                <textarea id="source_text" name="source_text" rows="12" required
                    class="w-full rounded-lg border-gray-300 shadow-sm font-mono text-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="2 Coca Cola 12 MAD

1 Huile Lesieur 35 MAD

3 Savon Dove 8 MAD">{{ old('source_text') }}</textarea>

                <p class="mt-2 text-xs text-gray-500">
                    Minimum 10 caractères, maximum 5000 caractères.
                </p>
            </div>

            <!-- Info Card -->
            <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4">
                <h3 class="font-semibold text-indigo-900">
                    Traitement automatique
                </h3>

                <p class="mt-1 text-sm text-indigo-700">
                    Après l'envoi, le reçu sera traité en arrière-plan.
                    Son statut passera automatiquement de
                    <strong>En attente</strong>
                    à
                    <strong>Traité</strong>
                    ou
                    <strong>Échoué</strong>.
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Lancer l'extraction
                </button>

            </div>

        </form>

    </div>

</div>