<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer; // FIX: Menggunakan Model Customer
use App\Models\Vendor; 
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        // Kebijakan: Hanya Admin yang boleh mengelola user.
        $this->middleware('auth:admin');
    }

    /**
     * Menampilkan daftar semua user (Customer, Vendor, Admin).
     */
    public function index()
    {
        // Mengambil semua Customer
        $customers = Customer::latest()->paginate(10, ['*'], 'customers');
        
        // Mengambil semua Vendor
        $vendors = Vendor::latest()->paginate(10, ['*'], 'vendors');
        
        // Mengambil Admin selain yang sedang login
        $admins = Admin::where('id', '!=', Auth::guard('admin')->id())->latest()->paginate(10, ['*'], 'admins');

        return view('admin.users.index', compact('customers', 'vendors', 'admins'));
    }

    /**
     * Menampilkan formulir untuk mengedit user (digabung untuk semua role).
     */
    public function edit($role, $id)
    {
        if ($role === 'customer') {
            $user = Customer::findOrFail($id);
        } elseif ($role === 'vendor') {
            $user = Vendor::findOrFail($id);
        } elseif ($role === 'admin') {
            $user = Admin::findOrFail($id);
        } else {
            abort(404);
        }

        // Kita bisa menggunakan Policy di sini, tapi untuk Admin diasumsikan memiliki otoritas penuh.
        return view('admin.users.edit', compact('user', 'role'));
    }

    /**
     * Memperbarui data user.
     */
    public function update(Request $request, $role, $id)
    {
        $model = null;
        $tableName = '';

        if ($role === 'customer') { $model = Customer::findOrFail($id); $tableName = 'customers'; }
        elseif ($role === 'vendor') { $model = Vendor::findOrFail($id); $tableName = 'vendors'; }
        elseif ($role === 'admin') { $model = Admin::findOrFail($id); $tableName = 'admins'; }

        if (!$model) { abort(404); }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique($tableName)->ignore($id)],
            'password' => ['nullable', 'min:8'],
        ]);
        
        $model->name = $validated['name'];
        $model->email = $validated['email'];
        if (!empty($validated['password'])) {
            $model->password = Hash::make($validated['password']);
        }
        $model->save();

        return Redirect::route('admin.users.index')->with('success', ucfirst($role) . ' berhasil diperbarui.');
    }

    /**
     * Menghapus user.
     */
    public function destroy($role, $id)
    {
        if ($role === 'customer') { Customer::findOrFail($id)->delete(); }
        elseif ($role === 'vendor') { Vendor::findOrFail($id)->delete(); }
        elseif ($role === 'admin') { 
            // Cek: Jangan izinkan Admin menghapus dirinya sendiri
            if (Auth::guard('admin')->id() == $id) {
                return Redirect::back()->with('error', 'Anda tidak dapat menghapus akun Admin Anda sendiri.');
            }
            Admin::findOrFail($id)->delete(); 
        } else {
            return Redirect::back()->with('error', 'Role tidak valid.');
        }
        
        return Redirect::route('admin.users.index')->with('success', ucfirst($role) . ' berhasil dihapus.');
    }
}