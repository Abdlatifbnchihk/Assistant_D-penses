<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecieptRequest;
use App\Enums\StatutReceiptEnum;
use App\Jobs\ExtraireDepensesDuRecu;
use Illuminate\Contracts\View\View;
use App\Models\Receipt;
use Illuminate\Http\RedirectResponse;

class ReceiptController extends Controller
{
    //

    public function index()
    {
        $receipt = Auth()->user()
            ->receipt()
            ->with('expenses')
            ->latest()
            ->get();

        return view('Receipt.index', compact('receipt'));
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
        // Ownership check — user can only see their own receipts.
        abort_if($recu->user_id !== auth()->id(), 403);

        $receipt = $recu->load('expenses');

        return View('Receipt.show', compact('receipt'));
    }

    // public function edit() {
    //     return view('Receipt.edit');
    // }

    // public function update(StoreRecieptRequest $request){

    // }

    public function destroy(Receipt $recu): RedirectResponse
    {
        abort_if($recu->user_id !== auth()->id(), 403);

        $recu->delete();

        return redirect()
            ->route('Receipt.index')
            ->with('message', 'Reçu supprimé.');
    }
}
