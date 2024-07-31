<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shoe extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'addons',
        'notes',
        'price',
        'order_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
