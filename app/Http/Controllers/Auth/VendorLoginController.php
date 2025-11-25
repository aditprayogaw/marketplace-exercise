<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VendorLoginController extends Controller
{
    /**
     * Tampilkan form login Vendor.
     */
    public function create()
    {
        return view('vendor.auth.login'); 
    }

    /**
     * Handle proses login Vendor.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Coba otentikasi menggunakan guard 'vendor'
        if (Auth::guard('vendor')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('vendor.dashboard')); 
        }

        // Jika otentikasi gagal
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Handle proses logout Vendor.
     */
    public function destroy(Request $request)
    {
        // Logout hanya dari guard 'vendor'
        Auth::guard('vendor')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman login vendor
        return redirect()->route('vendor.login');
    }
}
