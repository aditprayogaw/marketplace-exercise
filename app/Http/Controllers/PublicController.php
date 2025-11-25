<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CategoryService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class PublicController extends Controller
{
    protected $categoryService;
    protected $orderService;

    // Dependency Injection untuk Service Layer
    public function __construct(CategoryService $categoryService, OrderService $orderService)
    {
        $this->categoryService = $categoryService;
        $this->orderService = $orderService;
    }

    /**
     * Tampilkan halaman utama/daftar produk publik.
     * Route: / (homepage)
     */
    public function index(): View
    {
        // Ambil produk aktif dengan eager loading (Optimasi Query)
        $products = Product::active()
                           ->with(['vendor', 'category'])
                           ->latest()
                           ->paginate(12);

        $categories = $this->categoryService->getAllCategories();
        // Ambil cart dari session untuk menampilkan count di navigasi
        $cart = $this->orderService->getCart(); 
        
        return view('public.products.index', compact('products', 'categories', 'cart'));
    }

    /**
     * Tampilkan detail produk.
     * Route: /products/{product:slug}
     */
    public function show(Product $product): View
    {
        // Eager load reviews dan customer yang membuat review
        $product->load(['vendor', 'category', 'reviews.customer']);
        $cart = $this->orderService->getCart();
        
        return view('public.products.show', compact('product', 'cart'));
    }
}