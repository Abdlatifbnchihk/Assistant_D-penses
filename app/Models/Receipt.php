<?php

namespace App\Models;

use App\Enums\StatutReceiptEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receipt extends Model
{
    //

    protected $fillable = [
        'user_id',
        'source_text',
        'status',
        'payload_ia',
    ];

    protected function casts(): array{
        return [
            'status' => StatutReceiptEnum::class,
            'payload_ia' => 'array',
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }


    public function expenses(): HasMany {
        return $this->hasMany(Expenses::class, 'recu_id');
    }
}
