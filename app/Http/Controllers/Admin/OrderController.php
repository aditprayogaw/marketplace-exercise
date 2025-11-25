<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        // Middleware sudah di route group (auth:admin)
    }

    /**
     * Tampilkan daftar semua pesanan.
     */
    public function index()
    {
        // Admin melihat semua order
        // Eager load customer (Optimasi Query)
        $orders = Order::with(['customer'])->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail pesanan.
     */
    public function show(Order $order)
    {
        // Eager load customer, items, dan produk di dalamnya
        $order->load('customer', 'items.product');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Perbarui status pesanan (Untuk Admin).
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Validasi status
        $request->validate([
            'status' => ['required', 'string', 'in:Pending,Paid,Shipped,Completed,Cancelled'],
        ]);

        $this->orderService->updateOrderStatus($order, $request->status);

        return Redirect::route('admin.orders.show', $order)->with('success', 'Status pesanan berhasil diperbarui menjadi ' . $request->status);
    }
}