<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $fillable = [
       'kecamatan',
       'laundry_id'
    ];

    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }
}
