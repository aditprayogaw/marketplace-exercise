<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    /**
     * Buat instance event baru.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}