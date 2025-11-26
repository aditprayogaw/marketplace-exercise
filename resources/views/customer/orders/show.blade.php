<?php 
use Illuminate\Support\Facades\Auth;
use App\Models\Reviews; 
$customer = Auth::guard('customer')->user();
?>
<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pesanan #') . $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 space-y-6">

                {{-- Ringkasan Pesanan --}}
                <div class="flex justify-between items-start border-b pb-4">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900">Pesanan #{{ $order->id }}</h3>
                        <p class="text-gray-500 mt-1">Tanggal Pesanan: {{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right">
                        {{-- Status Badge --}}
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                            @if($order->status === 'Completed') 'bg-green-100 text-green-800'
                            @elseif($order->status === 'Shipped') 'bg-blue-100 text-blue-800'
                            @elseif($order->status === 'Paid') 'bg-indigo-100 text-indigo-800'
                            @elseif($order->status === 'Cancelled') 'bg-red-100 text-red-800'
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                        <p class="text-xl font-bold text-gray-900 mt-2">Total: Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Daftar Item Pesanan --}}
                <h4 class="text-2xl font-bold text-gray-800 pt-6 border-t">Item dalam Pesanan</h4>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 p-4 border border-gray-200 rounded-lg bg-gray-50 hover:bg-white transition duration-150">
                            
                            {{-- Kolom 1 & 2: Detail Produk & Harga --}}
                            <div class="md:col-span-2 flex items-center space-x-4">
                                <a href="{{ route('products.show', $item->product->slug) }}">
                                    <img src="{{ Storage::url($item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-md border">
                                </a>
                                <div>
                                    <p class="font-semibold text-gray-800 text-lg hover:text-indigo-600 transition">
                                        <a href="{{ route('products.show', $item->product->slug) }}">
                                            {{ $item->product->name }}
                                        </a>
                                    </p>
                                    <p class="text-sm text-gray-500">Vendor: {{ $item->product->vendor->store_name }}</p>
                                </div>
                            </div>

                            {{-- Kolom 3: Qty & Subtotal --}}
                            <div class="text-right md:col-span-1 flex flex-col justify-center">
                                <p class="text-gray-700 font-medium">{{ $item->qty }} x Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                <p class="text-lg font-bold text-indigo-600">Rp{{ number_format($item->price * $item->qty, 0, ',', '.') }}</p>
                            </div>

                            {{-- Kolom 4 & 5: Tombol Review/Form --}}
                            <div class="md:col-span-2 flex flex-col justify-center">
                                @if($order->status === 'Completed')
                                    @php
                                        // Cek apakah Customer sudah mereview produk ini
                                        $productModel = $item->product;
                                        $hasReviewed = Reviews::where('customer_id', $customer->id)
                                                            ->where('product_id', $productModel->id)
                                                            ->exists();
                                    @endphp

                                    @if(!$hasReviewed)
                                        {{-- MEMUAT FORM REVIEW INLINE --}}
                                        @include('customer.reviews._form', ['product' => $productModel, 'customer' => $customer])
                                    @else
                                        <span class="inline-flex items-center justify-center w-full px-3 py-2 text-sm leading-4 font-medium rounded-md text-green-600 border border-green-300 bg-green-50">
                                            Sudah Diulas
                                        </span>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-500">Ulasan hanya bisa diberikan setelah pesanan Selesai.</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Informasi Pengiriman dan Pembeli --}}
                <div class="mt-6 p-6 border rounded-lg bg-gray-50">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Informasi Pembeli</h4>
                    <p><strong>Nama:</strong> {{ $order->customer->name }}</p>
                    <p><strong>Alamat:</strong> [Alamat Pengiriman akan ditampilkan di sini]</p>
                </div>

            </div>
        </div>
    </div>
</x-customer-layout>