<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CustomerLoginController extends Controller
{
    /**
     * Tampilkan form login Customer.
     */
    public function create()
    {
        return view('customer.auth.login'); 
    }

    /**
     * Handle proses login Customer.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Coba otentikasi menggunakan guard 'customer'
        if (Auth::guard('customer')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('customer.dashboard')); 
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Handle proses logout Customer.
     */
    public function destroy(Request $request)
    {
        // Logout hanya dari guard 'customer'
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman login customer
        return redirect()->route('customer.login');
    }
}