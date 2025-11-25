<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class VendorRegisterController extends Controller
{
    /**
     * Tampilkan form registrasi Vendor.
     */
    public function create()
    {
        return view('vendor.auth.register'); 
    }

    /**
     * Handle proses registrasi Vendor.
     */
    public function store(Request $request)
    {
        // 1. Validasi Data Input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'store_name' => ['required', 'string', 'max:255', 'unique:vendors'], // Wajib unik
            'email' => ['required', 'string', 'email', 'max:255', 'unique:vendors'], // Wajib unik
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Buat Record Vendor Baru
        $vendor = Vendor::create([
            'name' => $request->name,
            'store_name' => $request->store_name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password di-hash
        ]);

        // 3. Login Otomatis menggunakan guard 'vendor'
        Auth::guard('vendor')->login($vendor);

        $request->session()->regenerate();

        // Arahkan ke dashboard Vendor
        return redirect()->intended(route('vendor.dashboard'));
    }
}
