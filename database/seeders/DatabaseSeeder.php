<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Reviews;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Admin (1)
        Admin::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => \Hash::make('password'),
        ]);

        // 2. Data Vendor (10)
        Vendor::factory(10)->create();
        $vendorIds = Vendor::pluck('id');

        // 3. Data Customer (100)
        Customer::factory(100)->create();
        $customerIds = Customer::pluck('id');
        
        // 4. Data Kategori (10 Root & Children)
        $this->seedCategories();
        $categoryIds = Category::pluck('id');

        // 5. Data Produk (500)
        Product::factory(500)->make()
            ->each(function ($product) use ($vendorIds, $categoryIds) {
                $product->vendor_id = $vendorIds->random();
                $product->category_id = $categoryIds->random();
                $product->save();
            });
        $productIds = Product::pluck('id');


        // 6. Data Order (500) & OrderItem (dengan chunk)
        Order::factory(500)->make()
            ->chunk(100)
            ->each(function ($orderChunk) use ($customerIds, $productIds) {
                foreach ($orderChunk as $order) {
                    $order->customer_id = $customerIds->random();
                    $order->save();

                    // Buat 1-5 Order Items
                    $itemCount = rand(1, 5);
                    for ($i = 0; $i < $itemCount; $i++) {
                        $product = Product::find($productIds->random());
                        $order->items()->create([
                            'product_id' => $product->id,
                            'qty' => rand(1, 5),
                            'price' => $product->price,
                        ]);
                    }
                    // Hitung total harga order
                    $order->update(['total_price' => $order->items->sum(fn($item) => $item->qty * $item->price)]);
                }
            });
        
        // 7. Data Review (1000)
        Reviews::factory(1000)->make()
            ->chunk(100)
            ->each(function ($reviewChunk) use ($customerIds, $productIds) {
                foreach ($reviewChunk as $review) {
                    $review->customer_id = $customerIds->random();
                    $review->product_id = $productIds->random();
                    // Mengatasi duplikat key karena unique constraint customer_id + product_id
                    try {
                        $review->save();
                    } catch (\Exception $e) {
                        continue; 
                    }
                }
            });
    }

    protected function seedCategories(): void
    {
        // Membuat 10 Kategori Root
        $rootCategories = Category::factory(10)->create(['parent_id' => null]);

        // Membuat 1-3 sub-kategori untuk setiap root
        $rootCategories->each(function (Category $root) {
            Category::factory(rand(1, 3))->create(['parent_id' => $root->id]);
        });

        // Membuat 1-2 sub-sub-kategori untuk beberapa sub-kategori
        Category::whereNotNull('parent_id')->inRandomOrder()->limit(5)->get()->each(function (Category $sub) {
            Category::factory(rand(1, 2))->create(['parent_id' => $sub->id]);
        });
    }
}