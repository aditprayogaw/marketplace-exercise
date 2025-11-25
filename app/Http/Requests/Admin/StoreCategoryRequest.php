<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna berhak membuat request ini.
     * Hanya Admin yang boleh.
     */
    public function authorize(): bool
    {
        // Otorisasi: Harus login sebagai Admin
        return Auth::guard('admin')->check();
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request.
     */
    public function rules(): array
    {
        return [
            // Name: Wajib, string, max 255, harus unik di tabel 'categories'
            'name' => [
                'required', 
                'string', 
                'max:255', 
                'unique:categories,name' 
            ],
            // Parent ID: Opsional, harus ada di tabel 'categories'
            'parent_id' => [
                'nullable', 
                'exists:categories,id',
            ],
        ];
    }
    
    /**
     * Atur pesan error kustom.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori ini sudah ada.',
            'parent_id.exists' => 'Kategori induk yang dipilih tidak valid.',
        ];
    }
}