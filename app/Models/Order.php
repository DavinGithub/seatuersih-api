<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_type',
        'order_number',
        'address',
        'phone',
        'total_price',
        'pickup_date',
        'notes',
        'order_status',
        'laundry_id', // Update to laundry_id
    ];

    public function laundry()
    {
        return $this->belongsTo(Laundry::class); // Update relation
    }

    public function shoes()
    {
        return $this->hasMany(Shoe::class); // Assuming an order has many shoes
    }
}
