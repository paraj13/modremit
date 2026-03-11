<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FxRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_currency', 'to_currency', 'rate', 'is_active',
    ];

    protected $casts = [
        'rate'      => 'decimal:6',
        'is_active' => 'boolean',
    ];
}
