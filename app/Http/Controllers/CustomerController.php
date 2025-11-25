<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class CustomerController extends Controller 
{
    protected $orderService;

    // FIX: Inject OrderService ke Controller
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Tampilkan dashboard Customer.
     */
    public function dashboard()
    {
        // Ambil data order terbaru untuk tampilan dashboard
        $recentOrders = $this->orderService->getCustomerOrders(Auth::guard('customer')->id());
        
        return view('customer.dashboard', compact('recentOrders')); 
    }

    /**
     * Tampilkan riwayat pesanan Customer.
     */
    public function ordersIndex(): View
    {
        // ... (Logika sama)
        $customerId = Auth::guard('customer')->id();
        $orders = $this->orderService->getCustomerOrders($customerId);
        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail pesanan Customer.
     */
    public function ordersShow(Order $order): View
    {
        // ... (Logika sama)
        if ($order->customer_id !== Auth::guard('customer')->id()) {
            abort(403, 'Akses ditolak. Pesanan bukan milik Anda.');
        }
        $order->load('items.product');
        return view('customer.orders.show', compact('order'));
    }
}