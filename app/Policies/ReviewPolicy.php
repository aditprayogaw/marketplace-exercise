<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Order; 
use App\Models\Reviews; 
use Illuminate\Auth\Access\Response; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReviewPolicy
{
    public function create(Customer $customer, Product $product): Response
    {
        // 1. Cek Duplikat Review
        $hasReviewed = Reviews::where('customer_id', $customer->id)
                             ->where('product_id', $product->id)
                             ->exists();

        if ($hasReviewed) {
             return Response::deny('Anda sudah pernah memberi review pada produk ini.');
        }

        // 2. Cek Pembelian dan Status Completed (POIN KRITIS)
        $hasPurchasedAndCompleted = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.customer_id', $customer->id)
            ->where('order_items.product_id', $product->id)
            ->where('orders.status', 'Completed') // <-- Syarat Kunci
            ->exists(); 

        if (!$hasPurchasedAndCompleted) {
            return Response::deny('Anda hanya dapat memberi review pada produk yang sudah dibeli dan pesanan telah Selesai (Completed).');
        }

        return Response::allow();
    }
}