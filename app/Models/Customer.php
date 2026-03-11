<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agent_id', 'name', 'email', 'phone', 'date_of_birth',
        'nationality', 'address', 'kyc_status', 'sumsub_applicant_id', 'kyc_data',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'kyc_data'      => 'array',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function recipients()
    {
        return $this->hasMany(Recipient::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getKycBadgeAttribute(): string
    {
        return match($this->kyc_status) {
            'approved'  => 'success',
            'submitted' => 'warning',
            'rejected'  => 'danger',
            default     => 'secondary',
        };
    }
}
