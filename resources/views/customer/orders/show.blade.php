<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pesanan #') }}{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                
                {{-- Ringkasan Status --}}
                <div class="mb-6 p-4 border rounded-lg 
                    @if($order->status == 'Completed') 'border-green-300 bg-green-50'
                    @elseif($order->status == 'Pending') 'border-yellow-300 bg-yellow-50'
                    @else border-gray-300 bg-gray-50 @endif">
                    <h4 class="text-lg font-bold">Status Pesanan: {{ $order->status }}</h4>
                    <p class="text-sm text-gray-600">Dipesan pada: {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>

                <h3 class="text-xl font-bold mb-4 border-b pb-2">Item Pesanan ({{ $order->items->count() }} Produk)</h3>
                
                <table class="min-w-full divide-y divide-gray-200 mb-6">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{-- Menggunakan withTrashed() di OrderItem Model agar produk yang dihapus tetap terlihat --}}
                                    {{ $item->product->name ?? 'Produk Dihapus/Unknown' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    Rp{{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ $item->qty }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                                    Rp{{ number_format($item->price * $item->qty, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                {{-- Total --}}
                <div class="border-t pt-4 flex justify-end">
                    <div class="text-right">
                        <p class="text-lg font-bold">Total Pesanan:</p>
                        <p class="text-3xl font-extrabold text-indigo-600">
                            Rp{{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <a href="{{ route('customer.orders.index') }}" class="mt-6 block text-indigo-600 hover:underline">← Kembali ke Riwayat Pesanan</a>
            </div>
        </div>
    </div>
</x-customer-layout>