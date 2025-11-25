<x-vendor-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Produk Toko') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
        
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow-md">{{ session('success') }}</div>
        @endif

        <a href="{{ route('vendor.products.create') }}" class="inline-flex items-center px-5 py-2.5 bg-teal-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 transition duration-150 shadow-lg mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Produk Baru
        </a>

        <h3 class="text-xl font-semibold mt-6 mb-4">Daftar Produk Saya</h3>
        
        {{-- Filter Form --}}
        <form method="GET" action="{{ route('vendor.products.index') }}" class="mb-4 flex space-x-4 items-center">
            <select name="status" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm">
                <option value="">-- Semua Status --</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
            </select>
        </form>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($product->image_path)
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-10 h-10 object-cover rounded-full">
                                @else
                                    <span class="text-xs text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($product->status == 'active') 'bg-green-100 text-green-800'
                                    @elseif($product->status == 'draft') 'bg-yellow-100 text-yellow-800'
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('vendor.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 font-semibold">Edit</a>
                                
                                <form action="{{ route('vendor.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini? (Soft Delete)')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-transparent border-none cursor-pointer font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</x-vendor-layout>