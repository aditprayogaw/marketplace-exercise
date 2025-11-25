<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests; 

    /**
     * Tentukan URL untuk redirect otentikasi.
     * Ini digunakan oleh Middleware Auth saat pengguna tidak login.
     * @return string
     */
    protected function redirectTo()
    {
        // Jika route 'customer.login' ada, gunakan itu sebagai fallback utama.
        if (Route::has('customer.login')) {
            return route('customer.login');
        }
        
        // Default fallback
        return '/login';
    }
}