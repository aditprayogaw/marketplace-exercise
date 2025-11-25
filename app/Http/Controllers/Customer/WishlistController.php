<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\View\View;

class WishlistController extends Controller
{
    public function __construct()
    {
        // Wajib Login Customer untuk mengakses Wishlist (diwarisi dari route group)
    }

    /**
     * Tampilkan daftar Wishlist Customer.
     */
    public function index(): View
    {
        $customer = Auth::guard('customer')->user();
        
        // Eager load produk dan vendor (Optimasi Query)
        // Relasi 'favorites' didefinisikan di Model Customer
        $wishlist = $customer->favorites()->with('vendor', 'category')->latest()->paginate(10);
        
        return view('customer.wishlist.index', compact('wishlist'));
    }

    /**
     * Tambah/Hapus (Toggle) produk dari Wishlist.
     */
    public function toggle(Product $product)
    {
        $customer = Auth::guard('customer')->user();
        
        // Relasi Many-to-Many menggunakan toggle()
        // Jika sudah ada, dihapus. Jika belum ada, ditambahkan.
        $attached = $customer->favorites()->toggle($product->id);
        
        // Cek apakah produk ditambahkan atau dihapus
        if (count($attached['attached']) > 0) {
            $message = $product->name . ' berhasil ditambahkan ke Wishlist.';
        } else {
            $message = $product->name . ' berhasil dihapus dari Wishlist.';
        }
        
        // Redirect kembali ke halaman sebelumnya
        return Redirect::back()->with('success', $message);
    }
}