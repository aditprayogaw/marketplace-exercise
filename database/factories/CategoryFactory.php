<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(rand(1, 2), true);
        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            // parent_id akan diisi secara manual di DatabaseSeeder untuk nested
            'parent_id' => null, 
        ];
    }
}
