<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            // customer_id dan product_id akan diisi di DatabaseSeeder
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph(rand(1, 3)),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
