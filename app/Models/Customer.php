<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected static function booted()
    {
        static::deleting(function ($customer) {
            if (!$customer->isForceDeleting() && $customer->email) {
                $customer->update([
                    'email' => $customer->email . '::deleted_' . now()->timestamp
                ]);
            }
        });
    }

    protected $guard = 'customer';

    protected $fillable = [
        'agent_id', 'name', 'email', 'phone', 'date_of_birth',
        'nationality', 'address', 'kyc_status', 'sumsub_applicant_id', 'kyc_data',
        'password', 'email_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'date_of_birth'     => 'date',
        'kyc_data'          => 'array',
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
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

    public function isKycApproved(): bool
    {
        return $this->kyc_status === 'approved';
    }
}
