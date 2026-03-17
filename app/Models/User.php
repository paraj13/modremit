<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'is_active', 'status', 'sumsub_applicant_id', 'kyc_status',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'agent_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'agent_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'agent_id');
    }

    public function fxQuotes()
    {
        return $this->hasMany(FxQuote::class, 'agent_id');
    }
}
