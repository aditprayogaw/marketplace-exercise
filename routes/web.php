<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\VendorLoginController;
use App\Http\Controllers\Auth\VendorRegisterController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Auth\CustomerLoginController;
use App\Http\Controllers\Auth\CustomerRegisterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PublicController; 
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\Admin\UserController;

// Import Controllers dengan Alias yang JELAS (agar tidak ambigu dengan namespace lain)
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Vendor\OrderController as VendorOrderController;

use Illuminate\Support\Facades\Route;

// =======================================================================
// 1. PUBLIC ROUTES & GLOBAL AUTH FALLBACK (Customer is primary user)
// =======================================================================

// A. Homepage & Detail Produk
Route::get('/', [PublicController::class, 'index'])->name('homepage'); 
Route::get('/products/{product:slug}', [PublicController::class, 'show'])->name('products.show'); 

// B. Customer Auth Routes (GLOBAL FALLBACK & Penamaan Konsisten)
// FIX: Memasukkan kembali Auth ke dalam group name('customer.')
Route::name('customer.')->group(function () {
    
    // Auth Routes
    Route::get('/register', [CustomerRegisterController::class, 'create'])->name('register'); // Result: customer.register
    Route::post('/register', [CustomerRegisterController::class, 'store']);
    
    Route::get('/login', [CustomerLoginController::class, 'create'])->name('login'); // Result: customer.login
    Route::post('/login', [CustomerLoginController::class, 'store']);
    
    // Logout Customer (Route ini memiliki nama 'customer.logout')
    Route::post('/logout', [CustomerLoginController::class, 'destroy'])->name('logout'); 
    
    // Cart Routes (Publik - Manipulasi Session Cart)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index'); 
    Route::post('/cart/add/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove'); 
});


// ===================================
// 2. ADMIN ROUTES
// ===================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // AUTHENTIKASI
    Route::get('/login', [AdminLoginController::class, 'create'])->name('login'); 
    Route::post('/login', [AdminLoginController::class, 'store']);
    Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
    
    // ROUTES YANG DILINDUNGI (POIN KRITIS: Group Middleware)
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // CRUD KATEGORI (AKTIFKAN)
        Route::resource('categories', AdminCategoryController::class); 

        // Order Management Admin
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
        Route::put('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
        
        // User Management (PLACEHOLDER)
        Route::get('users', [UserController::class, 'index'])->name('users.index'); 
        Route::get('users/{role}/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{role}/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{role}/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

// ===================================
// 3. VENDOR ROUTES
// ===================================
Route::prefix('vendor')->name('vendor.')->group(function () {
    
    // AUTHENTIKASI
    Route::get('/register', [VendorRegisterController::class, 'create'])->name('register'); 
    Route::post('/register', [VendorRegisterController::class, 'store']);
    Route::get('/login', [VendorLoginController::class, 'create'])->name('login'); 
    Route::post('/login', [VendorLoginController::class, 'store']);
    Route::post('/logout', [VendorLoginController::class, 'destroy'])->name('logout');
    
    // ROUTES YANG DILINDUNGI (POIN KRITIS: Group Middleware)
    Route::middleware(['auth:vendor'])->group(function () {
        Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
        
        // CRUD Produk Vendor
        Route::resource('products', VendorProductController::class);

        // Order Management Vendor
        Route::resource('orders', VendorOrderController::class)->only(['index', 'show']);
        Route::put('orders/{order}/status', [VendorOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
});


// =======================================================================
// 4. CUSTOMER PROTECTED ROUTES (Lanjutan dari Group 1)
// =======================================================================
Route::middleware(['auth:customer'])->name('customer.')->group(function () {
    // Dashboard Customer
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard'); 

    // Checkout
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('cart.process_checkout');
    
    // Order History & Detail
    Route::get('/orders', [CustomerController::class, 'ordersIndex'])->name('orders.index');
    Route::get('/orders/{order}', [CustomerController::class, 'ordersShow'])->name('orders.show');
    
    // REVIEW SYSTEM 
    Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('reviews.store');

    // WISHLIST / FAVORITE 
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/products/{product}/wishlist', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

});