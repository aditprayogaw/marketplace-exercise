<?php
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Order;
?>
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    @php
        // Ambil data user yang sedang login
        $admin = Auth::guard('admin')->user();
        
        // Ambil data statistik langsung dari Model (efisien)
        $totalProducts = Product::count();
        $totalVendors = Vendor::count();
        $totalPendingOrders = Order::where('status', 'Pending')->count(); 
    @endphp

    <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <h1 class="text-2xl font-bold mb-6 text-indigo-700 border-b pb-3">
            Selamat Datang, {{ $admin->name }}!
        </h1>
        
        {{-- Statistik Dashboard --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            {{-- Card 1: Total Produk --}}
            <div class="bg-indigo-50 p-6 rounded-xl shadow-md border-l-4 border-indigo-500">
                <h4 class="text-md font-semibold text-indigo-800">Total Produk</h4>
                <p class="text-4xl font-extrabold text-indigo-900 mt-2">{{ number_format($totalProducts) }}</p>
            </div>
            
            {{-- Card 2: Total Vendor --}}
            <div class="bg-green-50 p-6 rounded-xl shadow-md border-l-4 border-green-500">
                <h4 class="text-md font-semibold text-green-800">Total Vendor Aktif</h4>
                <p class="text-4xl font-extrabold text-green-900 mt-2">{{ number_format($totalVendors) }}</p>
            </div>
            
            {{-- Card 3: Order Pending --}}
            <div class="bg-red-50 p-6 rounded-xl shadow-md border-l-4 border-red-500">
                <h4 class="text-md font-semibold text-red-800">Order Pending</h4>
                <p class="text-4xl font-extrabold text-red-900 mt-2">{{ number_format($totalPendingOrders) }}</p>
            </div>
        </div>

        {{-- Navigasi Cepat Admin --}}
        <h3 class="text-xl font-semibold mt-6 mb-4 border-b pb-2">Aksi Cepat</h3>
        <div class="space-y-3 md:space-y-0 md:space-x-4 flex flex-col md:flex-row">
            
            <a href="{{ route('admin.categories.index') }}" class="w-full md:w-auto text-center bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 shadow-lg">
                Kelola Kategori
            </a>
            <a href="{{ route('admin.orders.index') }}" class="w-full md:w-auto text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 shadow-lg">
                Lihat Semua Pesanan
            </a>
            <a href="{{ route('admin.users.index') }}" class="w-full md:w-auto text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 shadow-lg">
                Kelola Pengguna
            </a>
        </div>
    </div>
</x-admin-layout>