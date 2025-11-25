<div class="flex flex-col space-y-2 p-4">
    <h3 class="text-xs font-semibold uppercase text-teal-300">Navigation</h3>

    {{-- Link Dashboard --}}
    <x-nav-link :href="route('vendor.dashboard')" :active="request()->routeIs('vendor.dashboard')" class="text-white hover:bg-teal-700 p-2 rounded-lg transition duration-150 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 001 1h2a1 1 0 001-1v-7M9 21h6" /></svg>
        Dashboard Toko
    </x-nav-link>

    <h3 class="text-xs font-semibold uppercase text-teal-300 pt-4">Manajemen Toko</h3>

    {{-- Link CRUD Produk --}}
    <x-nav-link :href="route('vendor.products.index')" :active="request()->routeIs('vendor.products.index')" class="text-white hover:bg-teal-700 p-2 rounded-lg transition duration-150 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
        Produk Saya
    </x-nav-link>

    {{-- Link Order Management --}}
    <x-nav-link :href="route('vendor.orders.index')" :active="request()->routeIs('vendor.orders.index')" class="text-white hover:bg-teal-700 p-2 rounded-lg transition duration-150 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
        Pesanan Masuk
    </x-nav-link>

    <h3 class="text-xs font-semibold uppercase text-teal-300 pt-4">Toko & Akun</h3>
    
    {{-- Link Review Toko (Placeholder) --}}
    <x-nav-link href="#" class="text-white hover:bg-teal-700 p-2 rounded-lg transition duration-150 opacity-50 cursor-not-allowed flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.152A1.5 1.5 0 0010 1.5a1.5 1.5 0 00-1.049.652L5.55 6.55l-4.59.38A1.5 1.5 0 00.12 7.72l3.41 3.32-1.28 4.75A1.5 1.5 0 004.5 17.5l4.59-2.31 4.59 2.31a1.5 1.5 0 002.66-1.55l-1.28-4.75 3.41-3.32a1.5 1.5 0 00-1.04-1.04l-4.59-.38L11.049 2.152z" /></svg>
        Review (Soon)
    </x-nav-link>
</div>