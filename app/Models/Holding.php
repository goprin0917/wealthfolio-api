<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Holding extends Model
{
    protected $fillable = [
        'user_id',
        'symbol',
        'name',
        'type',
        'quantity',
        'buy_price'
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string'
        ];
    }

    public function setSymbolAttribute(string $value): void
    {
        $this->attributes['symbol'] = strtoupper($value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
