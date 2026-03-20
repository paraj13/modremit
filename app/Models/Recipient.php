<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id', 'name', 'email', 'bank_name', 'account_number',
        'iban', 'swift_code', 'routing_number', 'sort_code',
        'ifsc_code', 'upi_id', 'country', 'is_active',
        'address_line_1', 'city', 'postal_code', 'state',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
