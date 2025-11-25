<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pesanan #') }}{{ $order->id }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Detail Pesanan #{{ $order->id }}</h1>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Kolom Kiri: Detail Pesanan -->
            <div class="w-full lg:w-2/3">
                
                <!-- Kartu Status & Total -->
                <div class="bg-indigo-50 shadow rounded-lg p-6 mb-6 border-l-4 border-indigo-500">
                    <h2 class="text-xl font-semibold mb-2 text-indigo-800">Ringkasan</h2>
                    <p class="text-lg">Status Saat Ini: 
                        <span class="px-3 py-1 inline-flex text-lg leading-5 font-bold rounded-full 
                            @if($order->status == 'Paid') 'bg-green-100 text-green-800'
                            @elseif($order->status == 'Shipped') 'bg-blue-100 text-blue-800'
                            @elseif($order->status == 'Completed') 'bg-indigo-100 text-indigo-800'
                            @elseif($order->status == 'Cancelled') 'bg-red-100 text-red-800'
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $order->status }}
                        </span>
                    </p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-3 border-t pt-2">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>

                <!-- Kartu Item Pesanan -->
                <div class="bg-white shadow rounded-lg p-6 mb-6 border border-gray-200">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Item yang Dipesan</h2>
                    <div class="divide-y divide-gray-200">
                        @foreach ($order->items as $item)
                            <div class="flex justify-between items-center py-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500">Vendor: {{ $item->product->vendor->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">Harga Satuan: Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                <p class="text-gray-700">{{ $item->qty }} x</p>
                                <p class="font-semibold text-gray-800">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Kartu Info Pelanggan -->
                <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Informasi Pelanggan</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Nama</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->customer->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->customer->email ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Alamat Pengiriman</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_address }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Kolom Kanan: Form Update Status -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white shadow rounded-lg p-6 sticky top-4 border border-indigo-200">
                    <h2 class="text-xl font-bold mb-4 text-gray-700">Ubah Status Pesanan</h2>
                    
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Pilih Status Baru</label>
                            <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Tertunda (Pending)</option>
                                <option value="Paid" {{ $order->status == 'Paid' ? 'selected' : '' }}>Dibayar (Paid)</option>
                                <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Dikirim (Shipped)</option>
                                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                                <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Perbarui Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>