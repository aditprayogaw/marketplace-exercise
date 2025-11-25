<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
        
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow-md">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg shadow-md">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Kolom 1: Gambar & Info Dasar --}}
            <div class="lg:col-span-1">
                @if($product->image_path)
                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full rounded-xl shadow-lg border border-gray-200 object-cover aspect-square">
                @else
                    <div class="w-full h-80 bg-gray-200 flex items-center justify-center rounded-xl text-gray-500">Gambar Tidak Tersedia</div>
                @endif
                
                {{-- Review Rating --}}
                <div class="mt-4 text-center">
                    <span class="text-3xl font-extrabold text-yellow-600">{{ number_format($product->average_rating, 1) }}</span> / 5.0
                    <p class="text-sm text-gray-500">({{ $product->reviews->count() }} Review)</p>
                </div>
            </div>
            
            {{-- Kolom 2: Detail Produk & Aksi Beli --}}
            <div class="lg:col-span-2 space-y-4">
                <h1 class="text-4xl font-bold text-gray-900">{{ $product->name }}</h1>
                <p class="text-xl text-gray-600">Dijual oleh: <span class="font-semibold text-teal-600">{{ $product->vendor->store_name }}</span></p>

                <p class="text-5xl font-extrabold text-green-600 border-b pb-4">
                    Rp{{ number_format($product->price, 0, ',', '.') }}
                </p>

                <p class="text-gray-700">{{ $product->description }}</p>
                
                <div class="text-lg font-medium">
                    <p>Kategori: <span class="font-semibold">{{ $product->category->name }}</span></p>
                    <p>Stok: 
                        <span class="font-bold {{ $product->stock > 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $product->stock > 0 ? $product->stock . ' unit tersedia' : 'Habis' }}
                        </span>
                    </p>
                </div>
                
                {{-- Form Aksi (Cart & Wishlist) --}}
                <div class="flex space-x-4 items-center pt-4 border-t">
                    
                    @if($product->stock > 0)
                        {{-- Tombol Add to Cart --}}
                        <form action="{{ route('customer.cart.store', $product) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            <input type="number" name="qty" value="1" min="1" max="{{ $product->stock }}" required class="border-gray-300 rounded-lg shadow-sm w-20 p-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                Add to Cart
                            </button>
                        </form>
                    @endif

                    {{-- Tombol Toggle Wishlist --}}
                    @if (Auth::guard('customer')->check())
                        @php
                            // Cek apakah produk ini ada di wishlist customer yang sedang login
                            $isFavorited = Auth::guard('customer')->user()->favorites->contains($product->id);
                        @endphp
                        <form action="{{ route('customer.wishlist.toggle', $product) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="py-2.5 px-4 rounded-lg border font-semibold transition duration-150 {{ $isFavorited ? 'bg-red-500 text-white border-red-600 hover:bg-red-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>
                                {{ $isFavorited ? 'Hapus Favorit' : 'Tambah Favorit' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-10 border-t pt-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Ulasan & Rating</h2>
            
            {{-- Section 1: Formulir Review --}}
            @if (Auth::guard('customer')->check())
                {{-- Menggunakan Gate::allows() untuk Policy check --}}
                @can('create', [App\Models\Reviews::class, $product])
                    <div class="bg-indigo-50 p-6 rounded-lg shadow-md mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-indigo-700">Tulis Ulasan Anda</h3>
                        <form action="{{ route('customer.reviews.store', $product) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="rating" class="block font-medium text-sm text-gray-700">Rating (1-5)</label>
                                <select name="rating" id="rating" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
                                    <option value="">Pilih Rating</option>
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ $i }} Bintang</option>
                                    @endfor
                                </select>
                                @error('rating') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-4">
                                <label for="comment" class="block font-medium text-sm text-gray-700">Komentar</label>
                                <textarea name="comment" id="comment" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2"></textarea>
                                @error('comment') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md">Kirim Review</button>
                        </form>
                    </div>
                @else
                    <div class="bg-yellow-50 p-4 rounded-lg mb-8 text-sm text-yellow-800 border-l-4 border-yellow-500">
                        {{-- Menampilkan pesan error dari Policy --}}
                        {{ Gate::inspect('create', [App\Models\Reviews::class, $product])->message() }}
                    </div>
                @endcan
            @else
                <div class="bg-gray-50 p-4 rounded-lg mb-8 text-sm text-gray-800">
                    <a href="{{ route('customer.login') }}" class="text-indigo-600 hover:underline font-semibold">Login</a> untuk dapat memberikan ulasan pada produk ini.
                </div>
            @endif


            {{-- Section 2: Daftar Review yang Sudah Ada --}}
            <h3 class="text-2xl font-semibold mb-4">Daftar Ulasan ({{ $product->reviews->count() }})</h3>
            <div class="space-y-6">
                @forelse($product->reviews as $review)
                    <div class="p-4 border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <p class="font-bold text-gray-800">{{ $review->customer->name ?? 'Pengguna Anonim' }}</p>
                            <p class="text-sm text-yellow-600">
                                {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                            </p>
                        </div>
                        <p class="text-gray-700 italic">"{{ $review->comment }}"</p>
                        <p class="text-xs text-gray-500 mt-2 text-right">Direview pada: {{ $review->created_at->format('d M Y') }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
                @endforelse
            </div>

        </div>

    </div>
</x-customer-layout>