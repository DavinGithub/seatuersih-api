<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    use HasFactory;

    protected $fillable = [
       'kabupaten',
       'laundry_id'
    ];

    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }
}
