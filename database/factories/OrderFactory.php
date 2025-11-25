<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            // customer_id akan diisi di DatabaseSeeder
            'total_price' => 0, // Akan dihitung ulang
            'status' => $this->faker->randomElement(['Pending', 'Paid', 'Shipped', 'Completed', 'Cancelled']),
        ];
    }
}
