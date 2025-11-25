<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
{
        $storeName = $this->faker->unique()->company(); 

        return [
            'name' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(), 
            'email_verified_at' => now(),
            'password' => \Hash::make('password'), 
            'store_name' => $storeName,
            'address' => $this->faker->address(),
            'remember_token' => \Str::random(10),
        ];
    }
}
