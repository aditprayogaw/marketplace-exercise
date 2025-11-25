<?php
use Illuminate\Support\Facades\Auth;

// Deklarasi variabel dinamis di awal file
$guard = null;
$user = null;
$dashboardRoute = route('homepage'); 
$logoutRoute = null;

// POIN KRITIS: Penentuan Guard berdasarkan urutan prioritas
if (Auth::guard('admin')->check()) {
    $guard = 'admin';
    $user = Auth::guard('admin')->user();
    $dashboardRoute = route('admin.dashboard');
    $logoutRoute = route('admin.logout');
} elseif (Auth::guard('vendor')->check()) {
    $guard = 'vendor';
    $user = Auth::guard('vendor')->user();
    $dashboardRoute = route('vendor.dashboard');
    $logoutRoute = route('vendor.logout');
} elseif (Auth::guard('customer')->check()) {
    $guard = 'customer';
    $user = Auth::guard('customer')->user();
    $dashboardRoute = route('customer.dashboard');
    $logoutRoute = route('customer.logout');
}
?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ $dashboardRoute }}"> 
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    @if ($user)
                        <x-nav-link :href="$dashboardRoute" :active="request()->routeIs($guard . '.dashboard')">
                            {{ ucfirst($guard) }} Dashboard
                        </x-nav-link>
                        
                        <x-nav-link :href="route('homepage')" :active="request()->routeIs('homepage')">
                            Marketplace
                        </x-nav-link>
                        
                    @else
                        {{-- Jika tidak login, tampilkan link Login/Register publik --}}
                        <x-nav-link :href="route('customer.login')" :active="request()->routeIs('customer.login')">
                            Login
                        </x-nav-link>
                        <x-nav-link :href="route('customer.register')" :active="request()->routeIs('customer.register')">
                            Register
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if ($user)
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                {{-- FIX: Menggunakan $user->name yang sudah dihitung --}}
                                <div>{{ $user->name }} ({{ ucfirst($guard) }})</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            {{-- Content Dropdown --}}
                            <div class="px-4 py-2 text-xs text-gray-400">
                                Logged in as: {{ $user->email }}
                            </div>

                            <!-- Authentication Logout -->
                            <form method="POST" action="{{ $logoutRoute }}">
                                @csrf
                                <x-dropdown-link :href="$logoutRoute"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endif
            </div>
            
            {{-- Hamburger dan Responsive Menu (dihilangkan untuk brevity) --}}
        </div>
    </div>
</nav>