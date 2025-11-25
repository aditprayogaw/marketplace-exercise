<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Events\OrderProcessed; // Dibutuhkan di sini
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderService
{
    private const CART_KEY = 'shopping_cart';

    // ===================================
    // SHOPPING CART (Session Based)
    // ===================================
    
    /**
     * Mendapatkan isi Cart dari session.
     */
    public function getCart(): array
    {
        return Session::get(self::CART_KEY, []);
    }

    /**
     * Menambah produk ke Cart.
     */
    public function addToCart(Product $product, int $qty): array
    {
        $cart = $this->getCart();
        $productId = $product->id;

        $currentQty = $cart[$productId]['qty'] ?? 0;
        
        $cart[$productId] = [
            'product_id' => $productId,
            'name' => $product->name,
            'price' => $product->price,
            'qty' => $currentQty + $qty,
            'image_path' => $product->image_path,
        ];
        
        Session::put(self::CART_KEY, $cart);
        return $cart;
    }
    
    /**
     * Menghapus item dari Cart.
     */
    public function removeFromCart(Product $product): void
    {
        $cart = $this->getCart();
        $productId = $product->id;
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put(self::CART_KEY, $cart);
        }
    }

    /**
     * Menghitung total harga Cart.
     */
    public function getCartTotal(): float
    {
        $cart = $this->getCart();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }
        return $total;
    }
    
    /**
     * Menghapus Cart.
     */
    public function clearCart(): void
    {
        Session::forget(self::CART_KEY);
    }
    
    // ===================================
    // CHECKOUT & ORDER
    // ===================================

    /**
     * Proses Checkout dan pembuatan Order/OrderItem.
     */
    public function checkout(int $customerId): Order
    {
        $cart = $this->getCart();
        $total = $this->getCartTotal();

        if (empty($cart)) {
            throw new \Exception("Keranjang belanja kosong.");
        }
        
        // POIN KRITIS: Transaksi database
        return DB::transaction(function () use ($customerId, $total, $cart) {
            
            // 1. Buat Order
            $order = Order::create([
                'customer_id' => $customerId,
                'total_price' => $total,
                'status' => 'Pending', // Status awal
            ]);

            // 2. Buat Order Items
            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'], // Harga saat checkout
                ]);
            }

            // 3. Hapus Cart
            $this->clearCart();
            
            // 4. Picu Event untuk pengurangan stok
            OrderProcessed::dispatch($order); 

            return $order;
        });
    }

    /**
     * Mendapatkan daftar order milik Customer.
     */
    public function getCustomerOrders(int $customerId)
    {
        return Order::where('customer_id', $customerId)
                    ->with('items.product') 
                    ->latest()
                    ->paginate(10);
    }

    /**
     * Mendapatkan daftar order yang mengandung produk milik Vendor.
     */
    public function getVendorOrders(int $vendorId)
    {
        return Order::whereHas('items.product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->with(['customer', 'items.product'])
            ->latest()
            ->paginate(10);
    }

    /**
     * Update Status Order (Khusus Admin/Vendor)
     */
    public function updateOrderStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);
        return $order;
    }
}