<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'address',
        'phone',
        'total_price',
        'pickup_date',
        'notes',
        'user_id',
        'shoes_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shoes()
    {
        return $this->belongsTo(Shoe::class);
    }
}
