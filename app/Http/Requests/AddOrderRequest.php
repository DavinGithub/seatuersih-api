<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Adjust this according to your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_type' => 'required|string|in:regular_clean,deep_clean',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'total_price' => 'nullable|numeric',
            'pickup_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
            'order_status' => 'nullable|string|in:pending,driver on the way to location,shoe being cleaned,completed,decline',
        ];
    }
}
