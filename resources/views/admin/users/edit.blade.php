<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengguna: ') . $user->name }} ({{ ucfirst($role) }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.users.update', ['role' => $role, 'id' => $user->id]) }}">
                    @csrf
                    @method('PUT')

                    <!-- Nama -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nama Toko (Hanya untuk Vendor) -->
                    @if ($role === 'vendor')
                        <div class="mb-4">
                            <label for="store_name" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                            <input id="store_name" type="text" name="store_name" value="{{ old('store_name', $user->store_name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('store_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    @endif
                    
                    <h3 class="text-lg font-semibold mt-6 mb-4 border-t pt-4">Ganti Password (Opsional)</h3>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input id="password" type="password" name="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah password.</p>
                        @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="flex justify-end mt-6">
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition duration-150 mr-3">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition duration-150">
                            Perbarui Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>