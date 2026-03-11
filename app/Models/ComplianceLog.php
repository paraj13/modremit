<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', 'reason', 'notes', 'status', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'cleared'   => 'success',
            'reviewed'  => 'info',
            'escalated' => 'danger',
            default     => 'warning',
        };
    }
}
