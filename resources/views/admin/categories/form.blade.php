<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Kategori Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf

                    <!-- Nama Kategori -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori <span class="text-red-500">*</span></label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') @enderror" />
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori Induk (Parent) -->
                    <div class="mb-6">
                        <label for="parent_id" class="block text-sm font-medium text-gray-700">Kategori Induk (Opsional)</label>
                        <select id="parent_id" name="parent_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('parent_id') @enderror">
                            <option value="">-- Pilih Induk (Kategori Utama) --</option>
                            {{-- $parentCategories berasal dari CategoryService->getCategoryHierarchy() --}}
                            @foreach ($parentCategories as $parent)
                                <option value="{{ $parent->id }}" 
                                        {{ (string) old('parent_id') === (string) $parent->id ? 'selected' : '' }}>
                                    {{ $parent->hierarchy_name }} 
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition duration-150 mr-3">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>