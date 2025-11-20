<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeedDemoProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:seed-products {--kids=5} {--men=5} {--women=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed demo products for each category';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $kidsCount = $this->option('kids');
        $menCount = $this->option('men');
        $womenCount = $this->option('women');

        // Get categories
        $kidsCategory = Category::where('slug', 'kids')->first();
        $menCategory = Category::where('slug', 'men')->first();
        $womenCategory = Category::where('slug', 'women')->first();

        if (!$kidsCategory || !$menCategory || !$womenCategory) {
            $this->error('Categories not found. Please seed categories first.');
            return;
        }

        // Kids Products
        $kidsProducts = [
            ['name' => 'Colorful Kids T-Shirt', 'price' => 19.99, 'image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=400', 'description' => 'Comfortable and colorful cotton t-shirt for kids'],
            ['name' => 'Kids Hoodie Warm', 'price' => 39.99, 'image' => 'https://images.unsplash.com/photo-1507682066342-b3f221a02861?w=400', 'description' => 'Warm hoodie perfect for cold days'],
            ['name' => 'Kids Shorts Summer', 'price' => 24.99, 'image' => 'https://images.unsplash.com/photo-1546142584-06c8a0f39658?w=400', 'description' => 'Light shorts for summer activities'],
            ['name' => 'Kids Jacket Denim', 'price' => 44.99, 'image' => 'https://images.unsplash.com/photo-1551028719-00167b16ebc5?w=400', 'description' => 'Classic denim jacket for kids'],
            ['name' => 'Kids Joggers Comfort', 'price' => 34.99, 'image' => 'https://images.unsplash.com/photo-1506629082632-ec0e87a4c009?w=400', 'description' => 'Comfortable joggers for everyday wear'],
        ];

        // Men Products
        $menProducts = [
            ['name' => 'Classic White T-Shirt', 'price' => 29.99, 'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400', 'description' => 'Comfortable cotton t-shirt perfect for everyday wear'],
            ['name' => 'Denim Jeans Premium', 'price' => 79.99, 'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400', 'description' => 'Classic blue denim jeans with modern fit'],
            ['name' => 'Casual Polo Shirt', 'price' => 49.99, 'image' => 'https://images.unsplash.com/photo-1618231722033-6461a4b7d743?w=400', 'description' => 'Stylish polo shirt for casual outings'],
            ['name' => 'Sports Jacket Black', 'price' => 89.99, 'image' => 'https://images.unsplash.com/photo-1551585753-892d2cb80dbb?w=400', 'description' => 'Professional sports jacket for business casual'],
            ['name' => 'Casual Chino Pants', 'price' => 59.99, 'image' => 'https://images.unsplash.com/photo-1605233314367-30a4e1eaf27e?w=400', 'description' => 'Comfortable chino pants for everyday use'],
        ];

        // Women Products
        $womenProducts = [
            ['name' => 'Summer Dress Floral', 'price' => 59.99, 'image' => 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=400', 'description' => 'Beautiful floral print summer dress'],
            ['name' => 'Casual Blouse White', 'price' => 44.99, 'image' => 'https://images.unsplash.com/photo-1505330622279-719149adfc5e?w=400', 'description' => 'Elegant white blouse for any occasion'],
            ['name' => 'Skinny Jeans Blue', 'price' => 64.99, 'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400', 'description' => 'Perfect fitting skinny jeans in classic blue'],
            ['name' => 'Cardigan Sweater Cozy', 'price' => 54.99, 'image' => 'https://images.unsplash.com/photo-1551803641-acf40eb032f9?w=400', 'description' => 'Cozy cardigan sweater for comfort'],
            ['name' => 'Casual Sneakers White', 'price' => 74.99, 'image' => 'https://images.unsplash.com/photo-1552820728-8ac41f1ce891?w=400', 'description' => 'Stylish white sneakers for casual wear'],
        ];

        // Random Products (mixed categories)
        $randomProducts = [
            ['name' => 'Vintage Leather Jacket', 'price' => 129.99, 'category' => $menCategory, 'image' => 'https://images.unsplash.com/photo-1551028719-00167b16ebc5?w=400', 'description' => 'Timeless vintage leather jacket'],
            ['name' => 'Elegant Evening Dress', 'price' => 99.99, 'category' => $womenCategory, 'image' => 'https://images.unsplash.com/photo-1595777712802-76ca7ee5e4b8?w=400', 'description' => 'Perfect for special occasions'],
            ['name' => 'Sports Performance Shoes', 'price' => 89.99, 'category' => $menCategory, 'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400', 'description' => 'High-performance athletic shoes'],
            ['name' => 'Bohemian Festival Top', 'price' => 39.99, 'category' => $womenCategory, 'image' => 'https://images.unsplash.com/photo-1495777066379-b7f87d3bdc1f?w=400', 'description' => 'Trendy bohemian style top'],
            ['name' => 'Kids Adventure Backpack', 'price' => 34.99, 'category' => $kidsCategory, 'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400', 'description' => 'Perfect backpack for kids adventures'],
        ];

        // Seed Kids Products
        $this->info("Seeding {$kidsCount} Kids products...");
        for ($i = 0; $i < $kidsCount && $i < count($kidsProducts); $i++) {
            $product = $kidsProducts[$i];
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'slug' => Str::slug($product['name']),
                    'category_id' => $kidsCategory->id,
                    'price' => $product['price'],
                    'description' => $product['description'],
                    'main_image' => $product['image'],
                    'stock_quantity' => rand(20, 100),
                    'in_stock' => true,
                ]
            );
            $this->line("  ✓ {$product['name']}");
        }

        // Seed Men Products
        $this->info("Seeding {$menCount} Men products...");
        for ($i = 0; $i < $menCount && $i < count($menProducts); $i++) {
            $product = $menProducts[$i];
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'slug' => Str::slug($product['name']),
                    'category_id' => $menCategory->id,
                    'price' => $product['price'],
                    'description' => $product['description'],
                    'main_image' => $product['image'],
                    'stock_quantity' => rand(20, 100),
                    'in_stock' => true,
                ]
            );
            $this->line("  ✓ {$product['name']}");
        }

        // Seed Women Products
        $this->info("Seeding {$womenCount} Women products...");
        for ($i = 0; $i < $womenCount && $i < count($womenProducts); $i++) {
            $product = $womenProducts[$i];
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'slug' => Str::slug($product['name']),
                    'category_id' => $womenCategory->id,
                    'price' => $product['price'],
                    'description' => $product['description'],
                    'main_image' => $product['image'],
                    'stock_quantity' => rand(20, 100),
                    'in_stock' => true,
                ]
            );
            $this->line("  ✓ {$product['name']}");
        }

        $this->info("\n✅ Successfully seeded " . ($kidsCount + $menCount + $womenCount) . " demo products!");
    }
}
