{{-- @extends('layouts.app')

@section('content') --}}

<x-app-layout>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-gray-900">Mes reçus</h1>
        <a href="{{ route('Receipt.create') }}"
            class="bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-indigo-700">
            + Soumettre un reçu
        </a>
    </div>

    @if ($receipt->isEmpty())
        <div class="text-center py-16 bg-white rounded-lg border border-gray-200">
            <p class="text-gray-500 text-sm">Aucun reçu pour le moment.</p>
            <a href="{{ route('Receipt.create') }}"
                class="text-indigo-600 text-sm font-medium hover:underline mt-2 inline-block">
                Soumettre votre premier reçu
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-4 py-3">Date</th>
                        <th class="text-left px-4 py-3">Statut</th>
                        <th class="text-left px-4 py-3">Dépenses extraites</th>
                        <th class="text-right px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($receipt as $recu)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ $recu->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @php
                                                $colors = [
                                                    'pending' => 'bg-amber-100 text-amber-800',
                                                    'processed' => 'bg-green-100 text-green-800',
                                                    'failed' => 'bg-red-100 text-red-800',
                                                ];
                                            @endphp
                        <span
                                                class="text-xs font-medium px-2 py-1 rounded-full {{ $colors[$recu->status?->value] ?? 'bg-gray-100 text-gray-700' }}">
                                                {{ $recu->status?->label()}}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            @if ($recu->status?->value === 'processed')
                                                {{ $recu->expenses->count() }} article(s)
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right space-x-3">
                                            <a href="{{ route('Receipt.show', $recu) }}" class="text-indigo-600 hover:underline">
                                                Voir
                                            </a>
                                            <form action="{{ route('Receipt.destroy', $recu) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Supprimer ce reçu ?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="text-red-600 hover:underline">
                                                    Supprimer
                                                </button>

                                            </form>
                                        </td>
                                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-app-layout>

{{-- @endsection --}}