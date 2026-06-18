<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investment extends Model
{
    protected $fillable = [
        'investor_id',
        'investment_amount',
        'investment_date',
    ];

    protected function casts(): array
    {
        return [
            'investment_amount' => 'decimal:2',
            'investment_date' => 'date',
        ];
    }

    public function investor(): BelongsTo
    {
        return $this->belongsTo(Investor::class, 'investor_id', 'investor_id');
    }
}
