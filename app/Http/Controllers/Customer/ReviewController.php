<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Reviews; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate; 

class ReviewController extends Controller
{
    // ... (metode construct)

    public function store(Request $request, Product $product)
    {
        $customer = Auth::guard('customer')->user();
        

        $this->authorize('create', [Reviews::class, $product]);

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        Reviews::create([ 
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return Redirect::back()->with('success', 'Terima kasih, review Anda berhasil disimpan!');
    }
}