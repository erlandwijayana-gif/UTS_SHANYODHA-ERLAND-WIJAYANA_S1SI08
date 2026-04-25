<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'order_id',
        'name',
        'email',
        'phone',
        'amount',
        'message',
        'status',
        'payment_type',
        'snap_token',
        'midtrans_response',
    ];

    protected $casts = [
        'midtrans_response' => 'array',
    ];
}