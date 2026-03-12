<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FxQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id', 'quote_id', 'chf_amount', 'send_amount', 'target_amount', 'rate',
        'fee', 'agent_commission', 'admin_commission', 'from_currency', 'to_currency', 'expires_at', 'raw_response',
    ];

    protected $casts = [
        'expires_at'       => 'datetime',
        'raw_response'     => 'array',
        'chf_amount'       => 'decimal:4',
        'send_amount'      => 'decimal:4',
        'target_amount'    => 'decimal:4',
        'rate'             => 'decimal:6',
        'fee'              => 'decimal:4',
        'agent_commission' => 'decimal:4',
        'admin_commission' => 'decimal:4',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
