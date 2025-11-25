<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna berhak membuat request ini.
     * Hanya Vendor yang login yang boleh.
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
        return [
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:1000'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            // Image: Wajib ada saat membuat produk baru, harus gambar, max 2MB.
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], 
        ];
    }
    
    /**
     * Siapkan data untuk validasi (Mutator).
     * Secara otomatis menambahkan vendor_id ke data request sebelum validasi dijalankan.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'vendor_id' => Auth::guard('vendor')->id(),
        ]);
    }
    
    /**
     * Atur pesan error kustom.
     */
    public function messages(): array
    {
        return [
            'image.required' => 'Foto produk wajib diunggah.',
            'image.max' => 'Ukuran foto maksimal 2MB.',
            'stock.min' => 'Stok produk tidak boleh kurang dari 0.',
        ];
    }
}