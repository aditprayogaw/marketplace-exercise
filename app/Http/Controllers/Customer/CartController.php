<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Validation\Rule;

class CartController extends Controller
{
    protected $orderService;

    // Dependency Injection
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Tampilkan isi Cart. (Public/Customer)
     */
    public function index()
    {
        $cart = $this->orderService->getCart();
        $total = $this->orderService->getCartTotal();
        
        // Kita akan membuat view ini di langkah berikutnya: 'customer.cart.index'
        return view('customer.cart.index', compact('cart', 'total'));
    }

    /**
     * Tambah produk ke Cart. (Public/Customer)
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
        ]);
        
        // POIN KRITIS: Cek stok
        $currentCart = $this->orderService->getCart();
        $qtyInCart = $currentCart[$product->id]['qty'] ?? 0;
        
        if ($product->stock < ($qtyInCart + $request->qty)) {
            return Redirect::back()->with('error', 'Stok produk "' . $product->name . '" hanya tersedia ' . $product->stock . ' unit.');
        }
        
        $this->orderService->addToCart($product, $request->qty);
        
        return Redirect::route('customer.cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }
    
    /**
     * Hapus item dari Cart. (Public/Customer)
     */
    public function remove(Product $product)
    {
        $this->orderService->removeFromCart($product);
        return Redirect::route('customer.cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }
    
    /**
     * Tampilkan form Checkout. (Wajib Login Customer)
     */
    public function checkout()
    {
        $cart = $this->orderService->getCart();
        $total = $this->orderService->getCartTotal();

        if (empty($cart)) {
            return Redirect::route('customer.cart.index')->with('error', 'Keranjang kosong, tidak bisa checkout.');
        }

        // Customer yang sedang login
        $customer = Auth::guard('customer')->user(); 
        
        return view('customer.cart.checkout', compact('cart', 'total', 'customer'));
    }

    /**
     * Proses Checkout dan buat Order. (Wajib Login Customer)
     */
    public function processCheckout(Request $request)
    {
        $customerId = Auth::guard('customer')->id();
        
        // Validasi tambahan jika perlu (misal: alamat, metode pembayaran)

        try {
            // Panggil Service untuk menjalankan transaksi Checkout
            // OrderService akan memanggil Event yang mengurangi stok
            $order = $this->orderService->checkout($customerId);
            
            // Redirect ke halaman konfirmasi pesanan (yang akan dibuat di langkah Orders)
            return Redirect::route('customer.orders.show', $order)->with('success', 'Pesanan berhasil dibuat! Stok produk telah dikurangi.');
        } catch (\Exception $e) {
            // Tangani error jika terjadi kegagalan transaksi
            return Redirect::route('customer.cart.checkout')->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }
}