<?php 
use Illuminate\Support\Facades\Auth;
$currentAdminId = Auth::guard('admin')->id();
?>
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Daftar Semua Pengguna</h1>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow-md">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg shadow-md">{{ session('error') }}</div>
        @endif

        {{-- Tabs Navigation --}}
        <div x-data="{ currentTab: 'customers' }" class="mt-4">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="currentTab = 'customers'" :class="{'border-indigo-500 text-indigo-600': currentTab === 'customers', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': currentTab !== 'customers'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition duration-150">
                        Customers ({{ $customers->total() }})
                    </button>
                    <button @click="currentTab = 'vendors'" :class="{'border-indigo-500 text-indigo-600': currentTab === 'vendors', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': currentTab !== 'vendors'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition duration-150">
                        Vendors ({{ $vendors->total() }})
                    </button>
                    <button @click="currentTab = 'admins'" :class="{'border-indigo-500 text-indigo-600': currentTab === 'admins', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': currentTab !== 'admins'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition duration-150">
                        Admins ({{ $admins->total() + 1 }})
                    </button>
                </nav>
            </div>

            {{-- Tab Content: Customers --}}
            <div x-show="currentTab === 'customers'" class="mt-6">
                <h3 class="text-xl font-semibold mb-4">Daftar Customers</h3>
                @include('admin.users._user_table', ['users' => $customers, 'role' => 'customer'])
                <div class="mt-4">{{ $customers->links() }}</div>
            </div>

            {{-- Tab Content: Vendors --}}
            <div x-show="currentTab === 'vendors'" class="mt-6">
                <h3 class="text-xl font-semibold mb-4">Daftar Vendors</h3>
                @include('admin.users._user_table', ['users' => $vendors, 'role' => 'vendor'])
                <div class="mt-4">{{ $vendors->links() }}</div>
            </div>

            {{-- Tab Content: Admins --}}
            <div x-show="currentTab === 'admins'" class="mt-6">
                <h3 class="text-xl font-semibold mb-4">Daftar Admins</h3>
                @include('admin.users._user_table', ['users' => $admins, 'role' => 'admin', 'currentAdminId' => $currentAdminId])
                <div class="mt-4">{{ $admins->links() }}</div>
            </div>
        </div>
    </div>
</x-admin-layout>