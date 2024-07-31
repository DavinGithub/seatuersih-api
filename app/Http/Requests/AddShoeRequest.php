<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddShoeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ganti dengan logika otorisasi Anda jika perlu
    }

    public function rules(): array
    {
        return [
            'brand' => 'required|string|max:255',
            'addons' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
            'price' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [       
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus berupa angka',
            // 'order_id.required' => 'Order ID harus diisi',
            // 'order_id.exists' => 'Order ID tidak valid',
        ];
    }
}
