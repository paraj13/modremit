<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id', 'chf_balance', 'total_received', 'total_sent_inr', 'total_commission',
    ];

    protected $casts = [
        'chf_balance'      => 'decimal:4',
        'total_received'   => 'decimal:4',
        'total_sent_inr'   => 'decimal:4',
        'total_commission' => 'decimal:4',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
