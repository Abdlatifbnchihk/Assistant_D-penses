<?php

namespace App\Models;

use App\Enums\CategorieExpensesEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expenses extends Model
{
    protected $fillable = [
        'recu_id',
        'libelle',
        'quantite',
        'prix_unitaire',
        'categorie',
    ];

    protected function casts(): array {
        return [
            'categorie' => CategorieExpensesEnum::class,
            'prix_unitaire' => 'decimal:2',
        ];
    }

    public function getPrixTotalAttribute(): float
    {
        return round((float) $this->quantite * (float) $this->prix_unitaire, 2);
    }

    // Relations
    public function receipt(): BelongsTo {
        return $this->belongsTo(Receipt::class, 'recu_id');
    }
}
