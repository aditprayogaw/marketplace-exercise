<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Scripts & STYLESHEETS (POIN KRITIS: Memuat CSS yang dikompilasi Tailwind) --}}
        @vite(['resources/css/app.css', 'resources/js/app.js']) 
    </head>
    
    <body class="font-sans antialiased">
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
                @include('layouts.navigation') 

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>