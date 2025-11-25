<?php

namespace App\Listeners;

use App\Events\OrderProcessed;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue; 

class DecreaseProductStock implements ShouldQueue
{ 
    /**
     * Handle event.
     */
    public function handle(OrderProcessed $event): void
    {
        // Iterasi melalui semua item dalam pesanan
        foreach ($event->order->items as $item) {
            
            // POIN KRITIS: Lakukan decrement stok di tabel produk
            Product::where('id', $item->product_id)->decrement('stock', $item->qty);
        }
    }
}