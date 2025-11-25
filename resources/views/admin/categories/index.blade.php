<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Kategori') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
        
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow-md">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg shadow-md">{{ session('error') }}</div>
        @endif

        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition duration-150 shadow-lg mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Kategori Baru
        </a>

        <h3 class="text-xl font-semibold mt-6 mb-4 border-b pb-2">Daftar Kategori</h3>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Induk (Parent)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($categories as $category)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{-- Menampilkan hierarki visual --}}
                                <span style="padding-left: {{ ($category->parent_id ? $category->level : 0) * 20 }}px;">
                                    {{ str_repeat('— ', $category->parent_id ? $category->level : 0) }}{{ $category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $category->parent->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 font-semibold">Edit</a>
                                
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->name }}? Ini akan menghapus semua sub-kategori!')">
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
        
        <!-- Menambahkan link paginasi -->
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
        
    </div>
</x-admin-layout>