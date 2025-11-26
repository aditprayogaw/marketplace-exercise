<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Marketplace Publik') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
        
        {{-- Pesan Status (Success/Error) --}}
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow-md">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg shadow-md">{{ session('error') }}</div>
        @endif
        
        {{-- Status Keranjang di Top --}}
        <div class="bg-indigo-50 p-4 rounded-lg shadow mb-6 flex justify-between items-center border border-indigo-200">
            <p class="text-indigo-800 font-medium">🛒 Keranjang Anda memiliki {{ count($cart) }} item.</p>
            <a href="{{ route('customer.cart.index') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold transition">
                Lihat Keranjang →
            </a>
        </div>

        <h3 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">Produk Terbaru</h3>

        {{-- Logika untuk mengecek apakah customer sedang login --}}
        @php
            $isCustomerLoggedIn = Auth::guard('customer')->check();
            $customer = $isCustomerLoggedIn ? Auth::guard('customer')->user() : null;
            // Jika login, ambil ID favorit untuk pengecekan cepat di loop
            $favoritedIds = $isCustomerLoggedIn ? $customer->favorites->pluck('id')->toArray() : [];
        @endphp

        {{-- Daftar Produk (Grid Layout) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                
                @php
                    $isFavorited = in_array($product->id, $favoritedIds);
                @endphp

                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col relative">
                    
                    {{-- Gambar Produk & Link --}}
                    <div class="h-48 bg-gray-200 flex items-center justify-center text-gray-500 font-semibold">
                        <a href="{{ route('products.show', ['product' => $product->slug]) }}" class="w-full h-full block">
                            @if($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-sm p-4 block">Gambar Tidak Tersedia</span>
                            @endif
                        </a>
                        
                        {{-- Tombol Wishlist di pojok kanan atas --}}
                        @if ($isCustomerLoggedIn)
                            <form action="{{ route('customer.wishlist.toggle', $product) }}" method="POST" class="absolute top-2 right-2 z-10">
                                @csrf
                                <button type="submit" title="{{ $isFavorited ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}"
                                    class="p-2 rounded-full shadow-lg transition duration-150 {{ $isFavorited ? 'bg-red-500 text-white hover:bg-red-600' : 'bg-white text-gray-500 hover:text-red-500' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="p-4 flex-grow flex flex-col justify-between">
                        <div>
                            <h4 class="text-xl font-semibold mb-1">
                                <a href="{{ route('products.show', ['product' => $product->slug]) }}" class="text-gray-800 hover:text-indigo-600 transition duration-150">
                                    {{ $product->name }}
                                </a>
                            </h4>
                            <p class="text-sm text-gray-500">oleh: <span class="font-medium text-teal-600">{{ $product->vendor->store_name }}</span></p>
                        </div>
                        
                        <div class="mt-3 flex justify-between items-center">
                            <p class="text-2xl font-extrabold text-green-600">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-xs font-medium px-3 py-1 rounded-full text-white {{ $product->stock > 0 ? 'bg-indigo-500' : 'bg-red-500' }}">
                                Stok: {{ $product->stock }}
                            </p>
                        </div>
                        
                        @if($product->stock > 0)
                            <!-- Form Add to Cart -->
                            <form action="{{ route('customer.cart.store', $product) }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" class="w-full py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 text-sm font-semibold shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    Add to Cart
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full py-2 bg-gray-300 text-gray-600 rounded-lg mt-4 text-sm font-semibold cursor-not-allowed">
                                Habis
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginasi --}}
        <div class="mt-8">
            {{ $products->links() }}
        </div>
            
    </div>
</x-customer-layout>