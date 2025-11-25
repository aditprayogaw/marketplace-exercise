<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    /**
     * Mendapatkan daftar produk Vendor, dengan eager loading Category dan Reviews.
     */
    public function getVendorProducts(int $vendorId, array $filters = [])
    {
        // POIN KRITIS: Menggunakan with(['category', 'reviews']) untuk menghindari N+1 problem
        $query = Product::where('vendor_id', $vendorId)
                        ->with(['category', 'reviews']); 

        // Menerapkan Scope Filtering (Jika ada)
        if (isset($filters['status']) && in_array($filters['status'], ['active', 'draft', 'out_of_stock'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        
        return $query->latest()->paginate(10);
    }
    
    /**
     * Membuat produk baru (Termasuk upload gambar dan penentuan status).
     */
    public function createProduct(array $data): Product
    {
        
        // 1. Logika Bisnis: Upload Gambar
        if (isset($data['image'])) {
            $data['image_path'] = $data['image']->store('products', 'public');
            unset($data['image']);
        } else {
            $data['image_path'] = null; 
        }

        $data['vendor_id'] = auth()->guard('vendor')->id();

        // 2. Logika Bisnis: Slug dan Status Otomatis
        $data['slug'] = Str::slug($data['name']);
        
        $data['status'] = $data['stock'] > 0 ? 'active' : 'out_of_stock';

        // 3. Simpan ke Database
        return Product::create($data); 
    }

    /**
     * Memperbarui produk yang ada.
     */
    public function updateProduct(Product $product, array $data): Product
    {
        // 1. Logika Bisnis: Upload Gambar (Jika ada gambar baru)
        if (isset($data['image'])) {
            // Hapus gambar lama jika ada
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $data['image']->store('products', 'public');
            unset($data['image']);
        }

        // 2. Logika Bisnis: Slug dan Status Otomatis
        if ($product->name !== $data['name']) {
            $data['slug'] = Str::slug($data['name']);
        }
        // Status diperbarui berdasarkan stok saat ini
        if (isset($data['stock'])) {
            $data['status'] = $data['stock'] > 0 ? 'active' : 'out_of_stock';
        }
        
        $product->update($data);
        return $product;
    }

    /**
     * Menghapus produk (Soft Delete).
     */
    public function deleteProduct(Product $product): bool
    {
        // Soft Delete
        return $product->delete();
    }
}