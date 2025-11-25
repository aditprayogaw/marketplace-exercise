<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'name' => 'Admin ' . $this->faker->lastName(),
            'email' => 'admin@' . $this->faker->unique()->safeEmailDomain(),
            'email_verified_at' => now(),
            // Menggunakan Hash::make() untuk memastikan password terenkripsi saat seeding
            'password' => Hash::make('password'), 
            'remember_token' => Str::random(10),
        ];
    }
}