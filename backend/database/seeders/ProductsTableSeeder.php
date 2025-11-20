<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get categories
        $menCategory = Category::where('slug', 'men')->first();
        $womenCategory = Category::where('slug', 'women')->first();
        $kidsCategory = Category::where('slug', 'kids')->first();

        if (!$menCategory || !$womenCategory || !$kidsCategory) {
            $this->command->error('Categories not found. Please run CategoriesTableSeeder first.');
            return;
        }

        $products = [
            [
                'name' => 'Classic White T-Shirt',
                'category_id' => $menCategory->id,
                'price' => 29.99,
                'description' => 'Comfortable cotton t-shirt perfect for everyday wear',
                'main_image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400',
                'stock_quantity' => 100,
            ],
            [
                'name' => 'Denim Jeans',
                'category_id' => $menCategory->id,
                'price' => 79.99,
                'description' => 'Classic blue denim jeans with modern fit',
                'main_image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400',
                'stock_quantity' => 50,
            ],
            [
                'name' => 'Summer Dress',
                'category_id' => $womenCategory->id,
                'price' => 59.99,
                'description' => 'Floral print summer dress',
                'main_image' => 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=400',
                'stock_quantity' => 75,
            ],
            [
                'name' => 'Kids Hoodie',
                'category_id' => $kidsCategory->id,
                'price' => 39.99,
                'description' => 'Warm and cozy hoodie for kids',
                'main_image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=400',
                'stock_quantity' => 60,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'slug' => Str::slug($product['name']),
                    'category_id' => $product['category_id'],
                    'price' => $product['price'],
                    'description' => $product['description'],
                    'main_image' => $product['main_image'],
                    'stock_quantity' => $product['stock_quantity'],
                    'in_stock' => true,
                ]
            );
        }

        $this->command->info('Products seeded successfully!');
    }
}
