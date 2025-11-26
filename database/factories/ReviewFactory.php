<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Reviews;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * Nama model yang sesuai dengan factory ini.
     *
     * @var string
     */
    protected $model = Reviews::class;

    /**
     * Definisikan state default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Catatan: ID customer dan product akan di-inject langsung di DatabaseSeeder 
        // untuk memastikan kinerja seeding yang lebih baik.
        return [
            // Rating 1 sampai 5
            'rating' => $this->faker->numberBetween(1, 5), 
            // 80% kemungkinan komentar diisi, 20% dikosongkan (hanya rating)
            'comment' => $this->faker->boolean(80) ? $this->faker->paragraph(3, true) : null,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}