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
            'address' => 'required|string|max:255',
            'phone' => 'required|integer',
            'total_price' => 'required|numeric',
            'pickup_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ];
    }
}
