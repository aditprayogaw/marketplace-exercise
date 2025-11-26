<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    /**
     * Tampilkan form login Admin.
     */
    public function create()
    {
        return view('admin.auth.login'); 
    }

    /**
     * Handle proses login Admin.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Coba otentikasi menggunakan guard 'admin'
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            
            // Regenerasi sesi untuk menghindari serangan fiksasi sesi
            $request->session()->regenerate();

            // Arahkan ke dashboard admin
            return redirect()->intended(route('admin.dashboard')); 
        }

        // Jika otentikasi gagal
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Handle proses logout Admin.
     */
    public function destroy(Request $request)
    {
        // Logout hanya dari guard 'admin'
        Auth::guard('admin')->logout();

        // Invalidasi sesi dan regenerasi token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman login admin
        return redirect()->route('admin.login');
    }
}
