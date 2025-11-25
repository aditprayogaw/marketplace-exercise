<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna berhak membuat request ini.
     * Harus login, tapi otorisasi kepemilikan (Policy) akan dilakukan di Controller.
     */
    public function authorize(): bool
    {
        // Otorisasi: Harus login sebagai Vendor
        return Auth::guard('vendor')->check(); 
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request.
     */
    public function rules(): array
    {
        // Mendapatkan instance Product yang sedang di-update untuk pengecekan unik
        $productId = $this->route('product')->id ?? null;
        
        return [
            // Name: Unik, tetapi abaikan ID produk yang sedang diedit
            'name' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('products', 'name')->ignore($productId)
            ],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:1000'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            // Image: Opsional saat update (nullable), tapi jika ada, harus valid
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], 
            // Status: Wajib dan harus salah satu dari nilai ENUM yang diizinkan
            'status' => ['required', Rule::in(['active', 'draft', 'out_of_stock'])],
        ];
    }
    
    /**
     * Atur pesan error kustom.
     */
    public function messages(): array
    {
        return [
            'image.max' => 'Ukuran foto maksimal 2MB.',
            'stock.min' => 'Stok produk tidak boleh kurang dari 0.',
            'status.required' => 'Status produk wajib dipilih.',
        ];
    }
}