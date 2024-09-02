<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_type',
        'external_id',
        'payment_method',
        'status',
        'amount',
        'payment_channel',
        'description',
        'payment_id',
        'paid_at',
        'order_id'
    ];

    // Relasi ke Payment
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
