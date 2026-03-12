<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agent_id', 'customer_id', 'recipient_id', 'fx_quote_id',
        'target_currency', 'chf_amount', 'target_amount', 'send_amount', 'commission', 'agent_commission', 'admin_commission', 'rate',
        'payment_ref', 'revolut_payment_id',
        'status', 'flagged', 'notes', 'failure_reason', 'metadata',
    ];

    protected $casts = [
        'chf_amount'       => 'decimal:4',
        'target_amount'    => 'decimal:4',
        'send_amount'      => 'decimal:4',
        'commission'       => 'decimal:4',
        'agent_commission' => 'decimal:4',
        'admin_commission' => 'decimal:4',
        'rate'             => 'decimal:6',
        'flagged'          => 'boolean',
        'metadata'         => 'array',
    ];

    const STATUS_PENDING    = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED  = 'completed';
    const STATUS_FAILED     = 'failed';

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function fxQuote()
    {
        return $this->belongsTo(FxQuote::class);
    }

    public function complianceLogs()
    {
        return $this->hasMany(ComplianceLog::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'completed'  => 'success',
            'processing' => 'info',
            'failed'     => 'danger',
            default      => 'warning',
        };
    }
}
