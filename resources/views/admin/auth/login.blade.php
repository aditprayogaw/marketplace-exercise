<x-guest-layout>
    <div class="flex flex-col justify-center items-center py-1 bg-gray-100">
        <div class="w-full max-w-sm px-8 py-10 bg-white rounded-lg shadow-lg border border-gray-200">
            
            <h1 class="text-3xl font-bold text-gray-800 text-center mb-2">
                Admin Access
            </h1>
            <p class="text-sm text-gray-500 text-center mb-8">
                Sign in to manage your marketplace.
            </p>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <!-- Email Address --><div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        autocomplete="username"
                    >
                    @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Password --><div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                    @error('password') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Remember Me --><div class="flex items-center mb-6">
                    <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                        Remember me
                    </label>
                </div>

                <div class="flex items-center justify-center">
                    <button 
                        type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150"
                    >
                        Log in
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</x-guest-layout>