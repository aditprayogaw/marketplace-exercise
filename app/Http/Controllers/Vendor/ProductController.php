<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller; 
use App\Models\Product;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Http\Requests\Vendor\StoreProductRequest; 
use App\Http\Requests\Vendor\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class ProductController extends Controller
{
    use AuthorizesRequests; // FIX: Memastikan trait di-load

    protected $productService;
    protected $categoryService;

    // Dependency Injection
    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        
        // Middleware: Harus login sebagai Vendor
        $this->middleware('auth:vendor');
        
        // Policy: Mengaitkan ProductPolicy dengan resource products
        $this->authorizeResource(Product::class, 'product'); 
    }

    /**
     * Tampilkan daftar produk milik Vendor yang sedang login.
     */
    public function index()
    {
        $vendorId = Auth::guard('vendor')->id();
        $filters = request()->only(['status', 'category_id']);
        
        // Panggil Service untuk mengambil produk dengan optimasi query
        $products = $this->productService->getVendorProducts($vendorId, $filters);
        
        // Dapatkan semua kategori untuk filter di view
        $categories = $this->categoryService->getAllCategories();

        return view('vendor.products.index', compact('products', 'categories'));
    }
    
    /**
     * Tampilkan form untuk membuat produk baru.
     */
    public function create()
    {
        // Policy check: authorize('create', Product::class) dilakukan oleh authorizeResource
        $categories = $this->categoryService->getAllCategories();
        return view('vendor.products.create', compact('categories'));
    }

    /**
     * Simpan produk baru.
     */
    public function store(StoreProductRequest $request)
    {
        // Policy check: authorize('create', Product::class) dilakukan oleh authorizeResource
        $this->productService->createProduct($request->validated());
        
        return Redirect::route('vendor.products.index')->with('success', 'Produk berhasil ditambahkan dan siap dijual.');
    }

    /**
     * Tampilkan form untuk mengedit produk.
     */
    public function edit(Product $product)
    {
        // Policy check: authorize('update', $product) dilakukan oleh authorizeResource
        $categories = $this->categoryService->getAllCategories();
        return view('vendor.products.edit', compact('product', 'categories'));
    }

    /**
     * Perbarui produk yang ada.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // Policy check: authorize('update', $product) dilakukan oleh authorizeResource
        $this->productService->updateProduct($product, $request->validated());
        return Redirect::route('vendor.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk (Soft Delete).
     */
    public function destroy(Product $product)
        // Policy check: authorize('delete', $product) dilakukan oleh authorizeResource
    {
        $this->productService->deleteProduct($product);
        return Redirect::route('vendor.products.index')->with('success', 'Produk berhasil dihapus (soft delete).');
    }

}