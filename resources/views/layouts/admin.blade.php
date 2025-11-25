<div class="min-h-screen bg-gray-100 flex">
    
    {{-- Sidebar (Menu Navigasi Kiri) --}}
    <div class="w-64 bg-gray-800 text-white shadow-xl flex-shrink-0">
        <div class="p-4 text-2xl font-bold border-b border-gray-700 text-indigo-400">
            ADMIN PANEL
        </div>
        @include('admin.sidebar')
    </div>

    {{-- Main Content --}}
    <div class="flex-grow">
        {{-- Header & Top Nav --}}
        @include('layouts.navigation') {{-- Kita tetap menggunakan navigasi Breeze untuk Topbar --}}

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</div>