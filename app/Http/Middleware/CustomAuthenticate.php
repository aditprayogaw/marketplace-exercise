<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class CustomAuthenticate extends Middleware
{
    /**
     * Dapatkan path tempat user harus dialihkan jika mereka tidak terautentikasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            
            // POIN KRITIS: Jika user mengakses /admin/* atau /vendor/*
            // Kita harus mengarahkan mereka ke halaman login yang spesifik.
            if ($request->routeIs('admin.*')) {
                return route('admin.login');
            }

            if ($request->routeIs('vendor.*')) {
                return route('vendor.login');
            }
            
            // Fallback utama untuk Customer dan Guest
            return route('customer.login');
        }
        return null;
    }
}