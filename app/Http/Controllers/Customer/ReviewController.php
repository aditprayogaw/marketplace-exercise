<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Reviews;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function __construct()
    {
        // Wajib Login Customer untuk memberi review
        // Middleware ini diterapkan di Route Group (routes/web.php)
    }

    /**
     * Menyimpan review baru.
     */
    public function store(Request $request, Product $product)
    {
        $customer = Auth::guard('customer')->user();

        // POIN KRITIS: Policy Check: Memastikan user berhak membuat review
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