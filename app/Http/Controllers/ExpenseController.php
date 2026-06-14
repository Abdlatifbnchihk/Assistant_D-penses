<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\Receipt;
use Illuminate\Http\RedirectResponse;

class ExpenseController extends Controller
{
    public function store(StoreExpenseRequest $request, Receipt $recu): RedirectResponse
    {
        abort_if($recu->user_id !== auth()->id(), 403);

        $recu->expenses()->create($request->validated());

        return redirect()
            ->route('Receipt.show', $recu)
            ->with('message', 'Dépense ajoutée avec succès.');
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): RedirectResponse
    {
        abort_if($expense->receipt->user_id !== auth()->id(), 403);

        $expense->update($request->validated());

        return redirect()
            ->route('Receipt.show', $expense->recu_id)
            ->with('message', 'Dépense modifiée avec succès.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        abort_if($expense->receipt->user_id !== auth()->id(), 403);

        $recuId = $expense->recu_id;
        $expense->delete();

        return redirect()
            ->route('Receipt.show', $recuId)
            ->with('message', 'Dépense supprimée.');
    }
}
