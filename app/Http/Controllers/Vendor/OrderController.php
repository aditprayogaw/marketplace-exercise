<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        // Hanya Vendor yang bisa mengakses
        $this->middleware('auth:vendor');
    }

    /**
     * Tampilkan daftar pesanan yang berisi produk milik Vendor ini.
     */
    public function index()
    {
        $vendorId = Auth::guard('vendor')->id();
        
        // Panggil Service untuk mengambil order yang relevan (Optimasi Query)
        $orders = $this->orderService->getVendorOrders($vendorId);
        
        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail pesanan.
     */
    public function show(Order $order)
    {
        // POIN KRITIS: Cek Otorisasi (Policy-like check)
        // Pastikan order ini mengandung minimal satu produk milik Vendor yang sedang login.
        if (!$order->items()->whereHas('product', fn($q) => $q->where('vendor_id', Auth::guard('vendor')->id()))->exists()) {
             abort(403, 'Akses ditolak. Pesanan ini tidak mengandung produk dari toko Anda.');
        }

        $order->load('customer', 'items.product');
        return view('vendor.orders.show', compact('order'));
    }

    /**
     * Perbarui status pesanan (misal: dari Paid ke Shipped).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:Pending,Paid,Shipped,Completed,Cancelled'],
        ]);
        
        // POIN KRITIS: Cek Otorisasi seperti di method show
        if (!$order->items()->whereHas('product', fn($q) => $q->where('vendor_id', Auth::guard('vendor')->id()))->exists()) {
             return Redirect::back()->with('error', 'Anda tidak berhak mengubah status pesanan ini.');
        }

        $this->orderService->updateOrderStatus($order, $request->status);

        return Redirect::route('vendor.orders.show', $order)->with('success', 'Status pesanan berhasil diperbarui menjadi ' . $request->status);
    }
}