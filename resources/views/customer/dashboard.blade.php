<?php
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Services\OrderService; 
?>
<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Saya') }}
        </h2>
    </x-slot>

    @php
        $customer = Auth::guard('customer')->user();
        
        // Menggunakan OrderService dan Query langsung untuk efisiensi
        // Asumsi OrderService sudah di-inject di CustomerController
        $totalOrders = Order::where('customer_id', $customer->id)->count();
        $pendingOrders = Order::where('customer_id', $customer->id)->where('status', 'Pending')->count();
        $wishlistCount = $customer->favorites()->count();
        
        // Ambil 5 order terbaru untuk ditampilkan
        $recentOrders = Order::where('customer_id', $customer->id)
                             ->with('items')
                             ->latest()
                             ->take(5)
                             ->get();
    @endphp

    <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <h1 class="text-3xl font-bold mb-6 text-indigo-700 border-b pb-3">
            Selamat Datang Kembali, {{ $customer->name }}!
        </h1>
        
        {{-- Statistik Dashboard --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            {{-- Card 1: Total Pesanan --}}
            <div class="bg-indigo-50 p-6 rounded-xl shadow-md border-l-4 border-indigo-500">
                <h4 class="text-md font-semibold text-indigo-800">Total Pesanan</h4>
                <p class="text-4xl font-extrabold text-indigo-900 mt-2">{{ number_format($totalOrders) }}</p>
            </div>
            
            {{-- Card 2: Pesanan Menunggu Pembayaran/Proses --}}
            <div class="bg-red-50 p-6 rounded-xl shadow-md border-l-4 border-red-500">
                <h4 class="text-md font-semibold text-red-800">Menunggu Proses</h4>
                <p class="text-4xl font-extrabold text-red-900 mt-2">{{ number_format($pendingOrders) }}</p>
            </div>
            
            {{-- Card 3: Wishlist --}}
            <div class="bg-yellow-50 p-6 rounded-xl shadow-md border-l-4 border-yellow-500">
                <h4 class="text-md font-semibold text-yellow-800">Wishlist Anda</h4>
                <p class="text-4xl font-extrabold text-yellow-900 mt-2">{{ number_format($wishlistCount) }}</p>
            </div>
        </div>

        {{-- Navigasi Cepat Customer --}}
        <h3 class="text-xl font-semibold mt-6 mb-4 border-b pb-2">Akses Cepat</h3>
        <div class="space-y-3 md:space-y-0 md:space-x-4 flex flex-col md:flex-row">
            
            <a href="{{ route('customer.orders.index') }}" class="w-full md:w-auto text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 shadow-lg">
                Riwayat Pesanan
            </a>
            <a href="{{ route('customer.wishlist.index') }}" class="w-full md:w-auto text-center bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 shadow-lg">
                Lihat Wishlist
            </a>
            <a href="{{ route('homepage') }}" class="w-full md:w-auto text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 shadow-lg">
                Lanjutkan Belanja
            </a>
        </div>
        
        {{-- Daftar Order Terbaru --}}
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4 border-b pb-2">5 Pesanan Terbaru</h3>
            
            @forelse($recentOrders as $order)
                <div class="p-4 mb-3 border rounded-lg bg-gray-50 shadow-sm flex justify-between items-center hover:bg-gray-100 transition duration-150">
                    <div>
                        <p class="font-semibold text-gray-800">Order #{{ $order->id }}</p>
                        <p class="text-sm text-gray-500">{{ $order->items->count() }} item • Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($order->status == 'Completed') 'bg-green-100 text-green-800'
                            @elseif($order->status == 'Shipped') 'bg-blue-100 text-blue-800'
                            @elseif($order->status == 'Pending') 'bg-yellow-100 text-yellow-800'
                            @else bg-red-100 text-red-800 @endif">
                            {{ $order->status }}
                        </span>
                        <a href="{{ route('customer.orders.show', $order) }}" class="ml-4 text-indigo-600 hover:text-indigo-900 text-sm font-semibold">Lihat Detail →</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Anda belum pernah membuat pesanan.</p>
            @endforelse
        </div>
        
    </div>
</x-customer-layout>