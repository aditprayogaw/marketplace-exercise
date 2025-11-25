<x-vendor-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('vendor.products.index') }}" class="text-teal-600 hover:text-teal-900 mb-4 inline-block font-semibold">← Kembali ke Daftar Produk</a>
            
            @include('vendor.products.form')
        </div>
    </div>
</x-vendor-layout>