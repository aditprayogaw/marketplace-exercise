<?php
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
?>
<x-vendor-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Toko') }}
        </h2>
    </x-slot>

    @php
        $vendor = Auth::guard('vendor')->user();
        $vendorId = $vendor->id;
        
        // Ambil data statistik dari Model
        $totalProducts = Product::where('vendor_id', $vendorId)->count();
        $activeProducts = Product::where('vendor_id', $vendorId)->active()->count();
        
        // Hitung pesanan pending (membutuhkan query yang efisien)
        $pendingOrders = Order::whereHas('items.product', fn($q) => $q->where('vendor_id', $vendorId))
                                ->where('status', 'Pending')
                                ->count();
    @endphp

    <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <h1 class="text-2xl font-bold mb-6 text-teal-700 border-b pb-3">
            Selamat Datang, {{ $vendor->store_name }}!
        </h1>
        
        {{-- Statistik Dashboard Vendor --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            {{-- Card 1: Total Produk --}}
            <div class="bg-indigo-50 p-6 rounded-xl shadow-md border-l-4 border-indigo-500">
                <h4 class="text-md font-semibold text-indigo-800">Total Produk</h4>
                <p class="text-4xl font-extrabold text-indigo-900 mt-2">{{ number_format($totalProducts) }}</p>
            </div>
            
            {{-- Card 2: Produk Aktif --}}
            <div class="bg-green-50 p-6 rounded-xl shadow-md border-l-4 border-green-500">
                <h4 class="text-md font-semibold text-green-800">Produk Aktif Dijual</h4>
                <p class="text-4xl font-extrabold text-green-900 mt-2">{{ number_format($activeProducts) }}</p>
            </div>
            
            {{-- Card 3: Pesanan Pending --}}
            <div class="bg-red-50 p-6 rounded-xl shadow-md border-l-4 border-red-500">
                <h4 class="text-md font-semibold text-red-800">Pesanan Pending Baru</h4>
                <p class="text-4xl font-extrabold text-red-900 mt-2">{{ number_format($pendingOrders) }}</p>
            </div>
        </div>

        {{-- Navigasi Cepat Vendor --}}
        <h3 class="text-xl font-semibold mt-6 mb-4 border-b pb-2">Aksi Cepat</h3>
        <div class="space-y-3 md:space-y-0 md:space-x-4 flex flex-col md:flex-row">
            
            <a href="{{ route('vendor.products.create') }}" class="w-full md:w-auto text-center bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 shadow-lg">
                Tambah Produk Baru
            </a>
            <a href="{{ route('vendor.products.index') }}" class="w-full md:w-auto text-center bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 shadow-lg">
                Lihat Semua Produk
            </a>
            <a href="{{ route('vendor.orders.index') }}" class="w-full md:w-auto text-center bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 shadow-lg">
                Kelola Pesanan Masuk
            </a>
        </div>
    </div>
</x-vendor-layout>