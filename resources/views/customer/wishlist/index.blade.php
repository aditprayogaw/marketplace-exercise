<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Wishlist Anda') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <h1 class="text-2xl font-bold mb-6 text-indigo-700 border-b pb-3">
            Produk Favorit (Wishlist)
        </h1>
        
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow-md">{{ session('success') }}</div>
        @endif

        @if ($wishlist->isEmpty())
            <div class="p-4 text-center bg-gray-50 rounded-lg">
                <p class="text-lg text-gray-600">Wishlist Anda masih kosong. Cari produk yang Anda sukai!</p>
                <a href="{{ route('homepage') }}" class="text-indigo-600 hover:text-indigo-900 mt-2 inline-block font-semibold">Mulai Belanja</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($wishlist as $product)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-md p-4 flex flex-col">
                        <h3 class="text-xl font-semibold text-gray-800 truncate">
                            <a href="{{ route('products.show', ['product' => $product->slug]) }}" class="hover:text-indigo-600">{{ $product->name }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">oleh: {{ $product->vendor->store_name }}</p>
                        
                        <div class="mt-3 flex justify-between items-center border-t pt-3">
                            <p class="text-2xl font-bold text-green-600">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            
                            {{-- Form Hapus dari Wishlist --}}
                            <form action="{{ route('customer.wishlist.toggle', $product) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 bg-red-100 hover:bg-red-200 py-1 px-3 rounded-full font-semibold text-sm transition duration-150">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $wishlist->links() }}
            </div>
        @endif
    </div>
</x-customer-layout>