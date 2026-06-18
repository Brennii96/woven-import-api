<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investor extends Model
{
    protected $primaryKey = 'investor_id';

    public $incrementing = false;

    protected $fillable = [
        'investor_id',
        'name',
        'age',
    ];

    protected $casts = [
        'age' => 'integer',
    ];

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class, 'investor_id', 'investor_id');
    }
}
