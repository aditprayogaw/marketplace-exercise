<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Konfirmasi Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Kolom Kiri: Detail Pengiriman/Customer --}}
                <div class="md:col-span-2 bg-white p-6 shadow-xl sm:rounded-lg border border-indigo-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Informasi Pembeli</h3>
                    
                    {{-- Form ini akan mengirimkan data, meskipun saat ini hanya mengirimkan token CSRF --}}
                    <form action="{{ route('customer.cart.process_checkout') }}" method="POST">
                        @csrf

                        <div class="space-y-3 text-sm text-gray-700">
                            <p><strong>Nama:</strong> {{ $customer->name }}</p>
                            <p><strong>Email:</strong> {{ $customer->email }}</p>
                            
                            {{-- Field Alamat Pengiriman (Sederhana) --}}
                            <h4 class="font-semibold mt-4">Alamat Pengiriman:</h4>
                            <textarea 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500" 
                                rows="3" 
                                name="address" 
                                placeholder="Masukkan Alamat Lengkap Anda..." 
                                required
                            >
                                {{ $customer->address ?? 'Silakan masukkan alamat Anda...' }}
                            </textarea>
                            
                            <p class="text-xs text-gray-500 mt-2">Pastikan alamat di atas sudah benar.</p>
                            
                            {{-- Input Tersembunyi untuk Memicu Checkout --}}
                            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        </div>

                        {{-- Kolom Kanan: Ringkasan & Tombol (Dipindahkan ke dalam form) --}}
                        <div class="mt-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Ringkasan Pesanan</h3>
                            
                            @foreach($cart as $item)
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>{{ $item['name'] }} (x{{ $item['qty'] }})</span>
                                    <span>Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach

                            <div class="border-t mt-4 pt-4 flex justify-between items-center">
                                <h4 class="text-lg font-bold">GRAND TOTAL:</h4>
                                <h4 class="text-2xl font-extrabold text-green-600">Rp{{ number_format($total, 0, ',', '.') }}</h4>
                            </div>
                            
                            <button type="submit" class="w-full py-3 mt-6 bg-green-600 text-white rounded-md font-semibold hover:bg-green-700 transition">
                                KONFIRMASI & BAYAR SEKARANG
                            </button>
                            <a href="{{ route('customer.cart.index') }}" class="block text-center mt-3 text-sm text-indigo-500 hover:text-indigo-700">← Kembali ke Keranjang</a>
                        </div>
                    </form>
                </div>

                {{-- Kolom Kanan Kosong pada Layout ini (Tergantung desain Anda) --}}
                <div class="md:col-span-1">
                    {{-- Ruang untuk metode pembayaran atau informasi lainnya --}}
                </div>
            </div>
        </div>
    </div>
</x-customer-layout>