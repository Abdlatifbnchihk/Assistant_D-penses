<?php

namespace App\Http\Requests;

use App\Enums\CategorieExpensesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'libelle' => ['required', 'string', 'max:255'],
            'quantite' => ['required', 'integer', 'min:1'],
            'prix_unitaire' => ['required', 'numeric', 'gt:0'],
            'categorie' => ['required', new Enum(CategorieExpensesEnum::class)],
        ];
    }
}
