<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    /**
     * Tampilkan dashboard Vendor.
     */
    public function dashboard()
    {
        // Data Vendor yang sedang login: Auth::guard('vendor')->user()
        return view('vendor.dashboard'); 
    }
}
