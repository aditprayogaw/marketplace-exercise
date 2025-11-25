<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(rand(3, 5), true);
        $stock = $this->faker->numberBetween(0, 200);
        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            // vendor_id dan category_id akan diisi di DatabaseSeeder
            'price' => $this->faker->randomFloat(2, 10000, 1000000), 
            'stock' => $stock,
            'description' => $this->faker->paragraph(3),
            'image_path' => 'products/placeholder_' . $this->faker->numberBetween(1, 5) . '.jpg',
            'status' => $stock > 0 ? 'active' : 'out_of_stock',
        ];
    }
}
