<div class="flex flex-col space-y-2 p-4">
    <h3 class="text-xs font-semibold uppercase text-gray-400">Navigation</h3>

    {{-- Link Dashboard --}}
    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-white hover:bg-gray-700 p-2 rounded-lg transition duration-150 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 001 1h2a1 1 0 001-1v-7M9 21h6" /></svg>
        Dashboard
    </x-nav-link>

    <h3 class="text-xs font-semibold uppercase text-gray-400 pt-4">Marketplace Management</h3>

    {{-- Link CRUD Kategori --}}
    <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.index')" class="text-white hover:bg-gray-700 p-2 rounded-lg transition duration-150 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
        Kelola Kategori
    </x-nav-link>

    {{-- Link Order Management --}}
    <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.index')" class="text-white hover:bg-gray-700 p-2 rounded-lg transition duration-150 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
        Kelola Pesanan
    </x-nav-link>

    <h3 class="text-xs font-semibold uppercase text-gray-400 pt-4">User Administration</h3>
    
    {{-- Link User Management (BARU) --}}
    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')" class="text-white hover:bg-gray-700 p-2 rounded-lg transition duration-150 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20v-2h-2m2 2h-2M15 4a6 6 0 100 12v-3h2M9 6a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        Kelola Pengguna
    </x-nav-link>
</div>