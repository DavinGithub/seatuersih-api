<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusToko extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_open',
    ];

    /**
     * Accessor untuk mengonversi is_open menjadi boolean
     */
    public function getIsOpenAttribute($value)
    {
        return (bool) $value;
    }

    /**
     * Mutator untuk mengonversi is_open menjadi integer saat menyimpan
     */
    public function setIsOpenAttribute($value)
    {
        $this->attributes['is_open'] = (int) filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
