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
            'detail_address' => 'required|string|max:255',
            'total_price' => 'nullable|numeric',
            'pickup_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
            'order_status' => 'nullable|string|in:pending,waiting_for_payment,in-progress,completed,decline',
            'kabupaten' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
        ];
    }
}
