<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_type',
        'review',
        'rating',
        'user_id',
        'review_date',
        'laundry_id',
        'order_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
