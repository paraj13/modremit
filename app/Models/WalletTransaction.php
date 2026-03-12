<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id', 'type', 'amount', 'currency', 'description',
        'reference_type', 'reference_id', 'status', 'created_by',
        'stripe_session_id', 'payment_method',
    ];

    protected $casts = [
        'amount' => 'decimal:4',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
