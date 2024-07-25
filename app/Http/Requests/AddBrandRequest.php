<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ganti dengan logika otorisasi Anda jika perlu
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:25',
        ];
    }

    public function messages(): array
    {
        return [     
            'name.required' => 'Nama sepatu harus diisi',
            // 'order_id.required' => 'Order ID harus diisi',
            // 'order_id.exists' => 'Order ID tidak valid',
        ];
    }
}
