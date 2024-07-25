<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
