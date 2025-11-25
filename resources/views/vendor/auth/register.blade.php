<x-guest-layout>
    <div class="flex flex-col justify-center items-center py-12 bg-gray-100">
        <div class="w-full max-w-lg px-8 py-10 bg-white rounded-xl shadow-2xl border border-gray-100">
            
            <h1 class="text-3xl font-extrabold text-teal-600 text-center mb-2">
                Daftar Toko Baru
            </h1>
            <p class="text-sm text-gray-500 text-center mb-8">
                Bergabunglah dan mulai jual produk Anda di marketplace.
            </p>

            <form method="POST" action="{{ route('vendor.register') }}">
                @csrf

                <!-- Nama Pribadi -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pribadi</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                    >
                    @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Nama Toko -->
                <div class="mb-4">
                    <label for="store_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Toko (Unik)</label>
                    <input id="store_name" type="text" name="store_name" value="{{ old('store_name') }}" required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                    >
                    @error('store_name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                
                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                    >
                    @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                    >
                    @error('password') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                    >
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('vendor.login') }}" class="text-sm text-gray-500 hover:text-teal-700 transition duration-150">
                        Sudah punya akun?
                    </a>
                    <button 
                        type="submit"
                        class="py-2.5 px-6 bg-teal-600 text-white font-semibold rounded-lg shadow-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition duration-150"
                    >
                        Daftar Toko
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</x-guest-layout>