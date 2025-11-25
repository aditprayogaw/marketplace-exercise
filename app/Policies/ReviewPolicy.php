<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Reviews; 
use Illuminate\Auth\Access\Response;

class ReviewPolicy
{
    /**
     * Tentukan apakah customer dapat membuat review untuk produk.
     * Izin diberikan jika customer telah membeli produk tersebut (order Completed) dan belum pernah review.
     */
    public function create(Customer $customer, Product $product): Response
    {
        // 1. Cek apakah customer SUDAH PERNAH MEMBELI dan Order sudah 'Completed'
        $hasPurchasedAndCompleted = Order::where('customer_id', $customer->id)
                             ->where('status', 'Completed')
                             ->whereHas('items', function ($query) use ($product) {
                                 $query->where('product_id', $product->id);
                             })
                             ->exists();

        // 2. Cek apakah Customer sudah pernah mereview produk ini sebelumnya
        $hasReviewed = Reviews::where('customer_id', $customer->id)
                             ->where('product_id', $product->id)
                             ->exists();

        if (!$hasPurchasedAndCompleted) {
            return Response::deny('Anda hanya dapat memberi review pada produk yang sudah dibeli dan pesanan telah Selesai (Completed).');
        }

        if ($hasReviewed) {
             return Response::deny('Anda sudah pernah memberi review pada produk ini.');
        }

        return Response::allow();
    }
}