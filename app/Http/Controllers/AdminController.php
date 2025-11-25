<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Tampilkan dashboard Admin.
     */
    public function dashboard()
    {
        // Kita bisa ambil data Admin yang sedang login: Auth::guard('admin')->user()
        return view('admin.dashboard'); 
    }
}
