<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CustomerRegisterController extends Controller
{
    /**
     * Tampilkan form registrasi Customer.
     */
    public function create()
    {
        return view('customer.auth.register');
    }

    /**
     * Handle proses registrasi Customer.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers'], // Unik di tabel customers
            'phone_number' => ['nullable', 'string', 'max:25'], // Gunakan max:25 sesuai perbaikan migrasi
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        // Login otomatis menggunakan guard 'customer'
        Auth::guard('customer')->login($customer);

        $request->session()->regenerate();

        // Arahkan ke dashboard Customer
        return redirect()->intended(route('customer.dashboard')); 
    }
}
