<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Sesuaikan dengan logika otorisasi Anda
    }

    public function rules()
    {
        return [
            'order_type' => 'required|string|in:Regular Clean,Deep Clean',
            'review' => 'required|string',
            'rating' => 'required|numeric|min:0.5|max:5',
            'laundry_id' => 'required|exists:laundries,id',
            'order_id' => 'required|exists:orders,id' 
        ];
    }
}
