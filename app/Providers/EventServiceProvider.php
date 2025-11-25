<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// Impor Event dan Listener kustom Anda
use App\Events\OrderProcessed;
use App\Listeners\DecreaseProductStock;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Pemetaan event ke listener untuk aplikasi.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        // POIN KRITIS: Daftarkan Event dan Listener Otomasi Stok
        OrderProcessed::class => [
            DecreaseProductStock::class,
        ],
    ];

    /**
     * Daftarkan event untuk aplikasi.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Tentukan apakah penemuan event harus diaktifkan.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}