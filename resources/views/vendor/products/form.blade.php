<div class="max-w-3xl mx-auto">
    @php
        $isEdit = isset($product);
        $action = $isEdit ? route('vendor.products.update', $product) : route('vendor.products.store');
        $method = $isEdit ? 'PUT' : 'POST';
    @endphp

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <!-- WAJIB: enctype="multipart/form-data" untuk upload file -->
        <form method="POST" action="{{ $action }}" enctype="multipart/form-data">
            @csrf
            @method($method)

            <!-- Nama Produk -->
            <div class="mb-4">
                <label for="name" class="block font-medium text-sm text-gray-700">Nama Produk</label>
                <input id="name" type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Kategori -->
                <div class="mb-4">
                    <label for="category_id" class="block font-medium text-sm text-gray-700">Kategori</label>
                    <select id="category_id" name="category_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option 
                                value="{{ $category->id }}" 
                                {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Status (Hanya saat Edit) -->
                <div class="mb-4">
                    <label for="status" class="block font-medium text-sm text-gray-700">Status Produk</label>
                    <select id="status" name="status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" {{ !$isEdit ? 'disabled' : '' }}>
                        <option value="draft" {{ old('status', $product->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status', $product->status ?? 'draft') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="out_of_stock" {{ old('status', $product->status ?? 'draft') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                    @if (!$isEdit)
                        <p class="text-xs text-gray-500 mt-1">Status akan otomatis menjadi 'Active' jika stok > 0 saat disimpan.</p>
                    @endif
                    @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Harga & Stok -->
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="price" class="block font-medium text-sm text-gray-700">Harga (Rp)</label>
                    <input id="price" type="number" name="price" value="{{ old('price', $product->price ?? '') }}" required min="1000" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('price') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mb-4">
                    <label for="stock" class="block font-medium text-sm text-gray-700">Stok</label>
                    <input id="stock" type="number" name="stock" value="{{ old('stock', $product->stock ?? '') }}" required min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('stock') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <!-- Deskripsi -->
            <div class="mb-4">
                <label for="description" class="block font-medium text-sm text-gray-700">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $product->description ?? '') }}</textarea>
                @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            
            <!-- Gambar Produk -->
            <div class="mb-6">
                <label for="image" class="block font-medium text-sm text-gray-700">{{ $isEdit ? 'Ganti Gambar' : 'Gambar Produk' }} {{ !$isEdit ? '(Wajib)' : '(Opsional)' }}</label>
                <input id="image" type="file" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                
                @if($isEdit && $product->image_path)
                    <p class="mt-2 text-sm text-gray-500">Gambar saat ini:</p>
                    <img src="{{ Storage::url($product->image_path) }}" alt="Current Image" class="w-24 h-24 object-cover rounded-lg border mt-1">
                @endif
            </div>


            <button type="submit" class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 transition duration-150">
                {{ $isEdit ? 'Update Produk' : 'Simpan Produk' }}
            </button>
        </form>
    </div>
</div>