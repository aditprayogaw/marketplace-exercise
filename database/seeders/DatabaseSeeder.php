<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Vendor;
use Database\Factories\ReviewFactory; 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * @var \Faker\Generator
     */
    protected $faker; 

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->faker = \Faker\Factory::create('id_ID');

        // 1. CLEAR EXISTING DATA
        $this->command->info('Menghapus data lama...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('admins')->truncate();
        DB::table('categories')->truncate();
        DB::table('vendors')->truncate();
        DB::table('customers')->truncate();
        DB::table('products')->truncate();
        DB::table('orders')->truncate();
        DB::table('order_items')->truncate();
        DB::table('reviews')->truncate();
        DB::table('favorites')->truncate(); 
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('Data lama berhasil dihapus.');


        // 2. ADMIN & CUSTOMER ACCOUNTS (Default)
        Admin::create([
            'name' => 'Admin Utama',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
        ]);
        Customer::create([
            'name' => 'Customer Utama',
            'email' => 'customer@mail.com',
            'password' => Hash::make('password'),
        ]);

        // 3. SEED CATEGORIES (10 Nested)
        $this->command->info('Membuat 10 Kategori...');
        $parentCategories = Category::factory(4)->create();
        $parentCategories->each(function ($parent) {
            Category::factory(2)->create(['parent_id' => $parent->id]);
        });
        Category::factory(2)->create(['parent_id' => null]); 
        
        // 4. VENDORS (10)
        $this->command->info('Membuat 10 Vendor...');
        $vendors = Vendor::factory(10)->create();
        
        // 5. CUSTOMERS (99 + 1 default = 100)
        $this->command->info('Membuat 100 Customer...');
        Customer::factory(99)->create();
        $customerIds = Customer::pluck('id')->all(); 

        // 6. PRODUCTS (500)
        $this->command->info('Membuat 500 Produk...');
        $categoryIds = Category::pluck('id')->all();
        $vendorIds = Vendor::pluck('id')->all();

        Product::factory(500)->make()->each(function ($product) use ($categoryIds, $vendorIds) {
            $product->category_id = $this->faker->randomElement($categoryIds);
            $product->vendor_id = $this->faker->randomElement($vendorIds);
            $product->save();
        });
        $productIds = Product::pluck('id')->all();
        
        // 7. ORDERS (500)
        $this->command->info('Membuat 500 Pesanan...');
        $ordersToCreate = 500;
        $orderItemsData = [];
        
        for ($i = 0; $i < $ordersToCreate; $i++) {
            $customer_id = $this->faker->randomElement($customerIds);
            $itemCount = $this->faker->numberBetween(1, 5);
            
            $status = $this->faker->randomElement(['Pending', 'Paid', 'Shipped', 'Completed', 'Cancelled']);
            $totalPrice = 0;
            
            $currentOrderItems = [];
            $usedProductIds = [];
            
            for ($j = 0; $j < $itemCount; $j++) {
                $productId = $this->faker->randomElement($productIds);
                while (in_array($productId, $usedProductIds)) {
                    $productId = $this->faker->randomElement($productIds);
                }
                $usedProductIds[] = $productId;

                $product = Product::find($productId);
                $qty = $this->faker->numberBetween(1, 3);
                $subtotal = $product->price * $qty; 
                $totalPrice += $subtotal;
                
                $currentOrderItems[] = [
                    'product_id' => $productId,
                    'price' => $product->price,
                    'qty' => $qty, 
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $order = [
                'customer_id' => $customer_id,
                'status' => $status,
                'total_price' => $totalPrice,
                'created_at' => now()->subDays($this->faker->numberBetween(1, 90)),
                'updated_at' => now(),
            ];

            $newOrderId = DB::table('orders')->insertGetId($order);

            foreach ($currentOrderItems as $item) {
                $item['order_id'] = $newOrderId;
                $orderItemsData[] = $item;
            }
        }
        
        DB::table('order_items')->insert($orderItemsData);


        // 8. REVIEWS (1000)
        $this->command->info('Membuat 1000 Reviews...');
        $reviewsToCreate = 1000;
        $reviewsData = [];
        $usedPairs = []; 
        
        if (empty($customerIds) || empty($productIds)) {
            $this->command->warn('Tidak ada Customer atau Produk, melewati seeding Reviews.');
        } else {
            $this->command->info('Menciptakan Review unik...');

            for ($i = 0; $i < $reviewsToCreate; $i++) {

                if (count($usedPairs) >= count($customerIds) * count($productIds)) {
                    $this->command->warn('Kehabisan pasangan Customer-Product unik. Hanya membuat ' . count($usedPairs) . ' review.');
                    break;
                } do {
                    $customerId = $this->faker->randomElement($customerIds);
                    $productId = $this->faker->randomElement($productIds);
                    $pairKey = $customerId . '-' . $productId;
                } while (in_array($pairKey, $usedPairs));

                $usedPairs[] = $pairKey;
                
                // Buat data review mentah menggunakan Factory::new()->makeOne()
                $factoryData = ReviewFactory::new()->makeOne()->toArray();

                $reviewsData[] = array_merge($factoryData, [
                    'customer_id' => $customerId,
                    'product_id' => $productId,
                    'created_at' => now()->subDays($this->faker->numberBetween(1, 60)),
                    'updated_at' => now(),
                ]);
            }
            
            // Sisipkan data secara masif menggunakan chunking
            collect($reviewsData)->chunk(500)->each(function ($chunk) {
                DB::table('reviews')->insert($chunk->toArray());
            });

            $this->command->info('Review berhasil disisipkan: ' . count($usedPairs) . ' entri unik.');
        }
    }
}