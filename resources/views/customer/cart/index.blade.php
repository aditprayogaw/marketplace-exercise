<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang Belanja Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Pesan Status --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
            @endif

            @if(empty($cart))
                <div class="p-6 bg-yellow-50 rounded-lg shadow-sm text-center">
                    <p class="text-lg text-yellow-800">Keranjang belanja Anda kosong.</p>
                    <a href="{{ route('homepage') }}" class="text-indigo-600 hover:underline mt-2 inline-block">Mulai belanja sekarang!</a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($cart as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item['name'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rp{{ number_format($item['price'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            {{ $item['qty'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                                            Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('customer.cart.remove', ['product' => $item['product_id']]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs bg-transparent border-none">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-6 border-t pt-4 flex justify-between items-center">
                            <h4 class="text-xl font-bold">Total Belanja:</h4>
                            <h4 class="text-2xl font-extrabold text-indigo-600">Rp{{ number_format($total, 0, ',', '.') }}</h4>
                        </div>

                        <div class="mt-8 text-right">
                            {{-- Cek apakah user sudah login. Jika belum, arahkan ke login --}}
                            @if (Auth::guard('customer')->check())
                                <a href="{{ route('customer.cart.checkout') }}" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-wider hover:bg-green-700 transition">
                                    Lanjutkan ke Checkout
                                </a>
                            @else
                                <a href="{{ route('customer.login') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-wider hover:bg-indigo-700 transition">
                                    Login untuk Checkout
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-customer-layout>