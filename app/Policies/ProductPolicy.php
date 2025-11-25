<?php

namespace App\Policies;

use App\Models\Vendor; // Menggunakan Model Vendor karena Vendor yang berinteraksi
use App\Models\Product;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Tentukan apakah Vendor dapat melihat (index) produk (melihat daftar produknya sendiri).
     */
    public function viewAny(Vendor $vendor): bool
    {
        return true; // Semua Vendor boleh melihat daftar produk di dashboard mereka.
    }

    /**
     * Tentukan apakah Vendor dapat membuat produk.
     */
    public function create(Vendor $vendor): bool
    {
        return true; // Semua Vendor yang login boleh membuat produk baru.
    }

    /**
     * Tentukan apakah Vendor dapat memperbarui produk.
     */
    public function update(Vendor $vendor, Product $product): Response
    {
        // POIN KRITIS: Vendor hanya boleh update produk yang vendor_id-nya miliknya.
        return $vendor->id === $product->vendor_id
                ? Response::allow()
                : Response::deny('Anda tidak memiliki izin untuk mengedit produk vendor lain.');
    }

    /**
     * Tentukan apakah Vendor dapat menghapus produk.
     */
    public function delete(Vendor $vendor, Product $product): Response
    {
        // Vendor hanya boleh menghapus produk yang vendor_id-nya miliknya.
        return $vendor->id === $product->vendor_id
                ? Response::allow()
                : Response::deny('Anda tidak memiliki izin untuk menghapus produk ini.');
    }
}