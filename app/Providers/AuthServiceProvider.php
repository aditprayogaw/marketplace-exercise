<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Review;
use App\Models\Product;
use App\Policies\ReviewPolicy;
use App\Policies\ProductPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Pemetaan model ke kebijakan (policies) untuk aplikasi.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Review::class => ReviewPolicy::class,
        Product::class => ProductPolicy::class,
    ];

    /**
     * Daftarkan layanan otentikasi/otorisasi aplikasi.
     */
    public function boot(): void
    {
        //
    }
}