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
        'detail_address',
        'phone',
        'total_price',
        'pickup_date',
        'notes',
        'order_status',
        'user_id',
        'laundry_id',
    ];

    protected $casts = [
        'total_price' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
        
    }

    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    public function shoes()
    {
        return $this->hasMany(Shoe::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
