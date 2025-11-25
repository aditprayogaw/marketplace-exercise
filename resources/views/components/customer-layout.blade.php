<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} | Marketplace</title>

        {{-- Scripts & STYLESHEETS (Memuat CSS yang dikompilasi Tailwind) --}}
        @vite(['resources/css/app.css', 'resources/js/app.js']) 
    </head>
    
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50 flex flex-col">
            
            {{-- Header & Top Nav --}}
            @include('layouts.navigation') 

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow border-b border-indigo-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-grow">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </div>
            </main>

            {{-- Footer (Optional, jika ingin membuat footer) --}}
            <footer class="bg-gray-800 text-white mt-auto">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm">
                    &copy; 2025 Marketplace Laravel Blade. All rights reserved.
                </div>
            </footer>
        </div>
    </body>
</html>