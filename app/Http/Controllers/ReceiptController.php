<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecieptRequest;
use App\Http\Requests\UpdateRecieptRequest;
use App\Enums\StatutReceiptEnum;
use App\Enums\CategorieExpensesEnum;
use App\Jobs\ExtraireDepensesDuRecu;
use Illuminate\Contracts\View\View;
use App\Models\Receipt;
use Illuminate\Http\RedirectResponse;

class ReceiptController extends Controller
{
    //

    public function index()
    {
        $query = Auth()->user()->receipt()->with('expenses');

        if ($categorie = request('categorie')) {
            $query->whereHas('expenses', function ($q) use ($categorie) {
                $q->where('categorie', $categorie);
            });
        }

        $receipt = $query->latest()->get();
        $categories = CategorieExpensesEnum::cases();
        $activeFilter = request('categorie');

        return view('Receipt.index', compact('receipt', 'categories', 'activeFilter'));
    }


    public function create()
    {
        return view('Receipt.create');
    }

    public function store(StoreRecieptRequest $request)
    {
        $receipt = Auth()->user()->receipt()->create(
            [
                'source_text' => $request->validated('source_text'),
                'status' => StatutReceiptEnum::Pending,
            ]
        );

        ExtraireDepensesDuRecu::dispatch($receipt->id);

        return redirect()
            ->route('Receipt.index')
            ->with('message', 'Reçu en cours de traitement...');
    }

    public function show(Receipt $recu): View
    {
        abort_if($recu->user_id !== auth()->id(), 403);

        $query = $recu->expenses();

        if ($categorie = request('categorie')) {
            $query->where('categorie', $categorie);
        }

        $expenses = $query->get();
        $receipt = $recu;
        $categories = CategorieExpensesEnum::cases();
        $activeFilter = request('categorie');

        return view('Receipt.show', compact('receipt', 'expenses', 'categories', 'activeFilter'));
    }

    public function edit(Receipt $recu): View
    {
        abort_if($recu->user_id !== auth()->id(), 403);

        return view('Receipt.edit', ['receipt' => $recu]);
    }

    public function update(UpdateRecieptRequest $request, Receipt $recu): RedirectResponse
    {
        abort_if($recu->user_id !== auth()->id(), 403);

        $recu->update([
            'source_text' => $request->validated('source_text'),
        ]);

        return redirect()
            ->route('Receipt.show', $recu)
            ->with('message', 'Reçu modifié avec succès.');
    }

    public function destroy(Receipt $recu): RedirectResponse
    {
        abort_if($recu->user_id !== auth()->id(), 403);

        $recu->delete();

        return redirect()
            ->route('Receipt.index')
            ->with('message', 'Reçu supprimé.');
    }

    function codeRabbit(){
        echo $name
        return 'hello world';
    }
}
