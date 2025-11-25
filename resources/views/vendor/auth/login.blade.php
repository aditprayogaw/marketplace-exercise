<x-guest-layout>
    <div class="flex flex-col justify-center items-center py-12 bg-gray-100">
        <div class="w-full max-w-md px-8 py-10 bg-white rounded-xl shadow-2xl border border-gray-100">
            
            <h1 class="text-3xl font-extrabold text-teal-600 text-center mb-2">
                VENDOR SIGN IN
            </h1>
            <p class="text-sm text-gray-500 text-center mb-8">
                Masuk untuk mengelola produk dan pesanan toko Anda.
            </p>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('vendor.login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Toko</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                        autocomplete="username"
                    >
                    @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                    >
                    @error('password') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex justify-between items-center mb-6">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="{{ route('vendor.register') }}" class="text-sm text-teal-500 hover:text-teal-700 transition duration-150">Daftar Sekarang</a>
                </div>

                <div class="flex items-center justify-center">
                    <button 
                        type="submit"
                        class="w-full py-2.5 bg-teal-600 text-white font-semibold rounded-lg shadow-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition duration-150"
                    >
                        Log in ke Toko
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</x-guest-layout>