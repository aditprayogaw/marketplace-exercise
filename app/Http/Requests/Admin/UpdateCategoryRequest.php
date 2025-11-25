<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna berhak membuat request ini.
     * Hanya Admin yang boleh.
     */
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request.
     */
    public function rules(): array
    {
        // Mendapatkan instance Category yang sedang di-update
        $categoryId = $this->route('category')->id ?? null;
        
        return [
            // Name: Unik, tetapi abaikan ID kategori ini
            'name' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('categories', 'name')->ignore($categoryId)
            ],
            // Parent ID: Opsional, harus ada di tabel 'categories', dan tidak boleh menjadi induknya sendiri.
            'parent_id' => [
                'nullable', 
                'exists:categories,id',
                // Mencegah kategori menjadi induknya sendiri
                Rule::notIn([$categoryId]) 
            ],
        ];
    }
    
    /**
     * Atur pesan error kustom.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'Nama kategori ini sudah ada.',
            'parent_id.not_in' => 'Kategori tidak boleh memilih dirinya sendiri sebagai induk.',
        ];
    }
}