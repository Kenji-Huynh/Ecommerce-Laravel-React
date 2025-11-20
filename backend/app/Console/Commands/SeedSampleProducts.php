<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeedSampleProducts extends Command
{
    protected $signature = 'demo:seed-products {--kids=5} {--men=5} {--women=5}';

    protected $description = 'Tạo nhanh sản phẩm demo: 5 Kids, 5 Men, 5 Women (mặc định)';

    public function handle(): int
    {
        $counts = [
            'Kids' => (int) $this->option('kids'),
            'Men'  => (int) $this->option('men'),
            'Women'=> (int) $this->option('women'),
        ];

        $this->info('🚀 Bắt đầu tạo sản phẩm demo...');

        foreach ($counts as $categoryName => $count) {
            if ($count <= 0) continue;

            $slug = Str::slug($categoryName);
            $category = Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $categoryName,
                    'image' => "categories/{$slug}.jpg",
                    'is_active' => true,
                    'sort_order' => 1,
                ]
            );

            // Tránh tạo trùng nhiều lần: nếu đã có đủ sản phẩm trong category thì bỏ qua
            $existing = Product::where('category_id', $category->id)->count();
            if ($existing >= $count) {
                $this->warn("Bỏ qua {$categoryName}: đã có {$existing}/{$count} sản phẩm.");
                continue;
            }

            for ($i = $existing + 1; $i <= $count; $i++) {
                $name = sprintf('%s Product %02d', $categoryName, $i);
                $price = $this->randomPriceForCategory($categoryName);
                $slugProduct = Str::slug($name) . '-' . Str::random(6);
                $mainImage = $this->placeholderImage($categoryName, $i);

                $product = Product::create([
                    'name' => $name,
                    'slug' => $slugProduct,
                    'description' => $this->fakeDescription($categoryName),
                    'price' => $price,
                    'compare_price' => $price + rand(5, 20),
                    'discount' => 0,
                    'sku' => strtoupper(substr($categoryName,0,1)) . '-' . strtoupper(Str::random(6)),
                    'is_new' => (bool)rand(0,1),
                    'is_featured' => (bool)rand(0,1),
                    'in_stock' => true,
                    'stock_quantity' => rand(10, 100),
                    'main_image' => $mainImage,
                    'category_id' => $category->id,
                    'sizes' => ['S','M','L'],
                    'colors' => ['black','white','blue'],
                    'tags' => [$slug,'demo'],
                    'rating' => rand(35, 50)/10, // 3.5 → 5.0
                    'reviews_count' => rand(0, 50),
                    'material' => 'Cotton',
                    'origin' => 'Vietnam',
                ]);

                // Thêm 2 ảnh phụ (placeholder)
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $this->placeholderImage($categoryName, $i, 2),
                    'sort_order' => 1,
                ]);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $this->placeholderImage($categoryName, $i, 3),
                    'sort_order' => 2,
                ]);

                $this->line("✔️  Đã tạo: {$name} ({$categoryName})");
            }
        }

        $this->info('✅ Hoàn tất tạo sản phẩm demo.');
        return Command::SUCCESS;
    }

    private function randomPriceForCategory(string $category): float
    {
        switch (strtolower($category)) {
            case 'kids':
                return round(rand(999, 2999) / 100, 2); // 9.99 - 29.99
            case 'men':
            case 'women':
            default:
                return round(rand(1999, 9999) / 100, 2); // 19.99 - 99.99
        }
    }

    private function placeholderImage(string $category, int $i, int $variant = 1): string
    {
        // Real working images from Unsplash - tested URLs
        $imagePool = [
            'Kids' => [
                'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=400&q=80',
                'https://images.unsplash.com/photo-1622290291468-a28f7a7dc238?auto=format&fit=crop&w=400&q=80',
                'https://images.unsplash.com/photo-1514090458221-65bb69cf63e6?auto=format&fit=crop&w=400&q=80',
                'https://images.unsplash.com/photo-1519689373023-dd07c7988603?auto=format&fit=crop&w=400&q=80',
                'https://images.unsplash.com/photo-1560114928-40f1f1eb26a0?auto=format&fit=crop&w=400&q=80',
            ],
            'Men' => [
                'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=400&q=80',
                'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=400&q=80',
                'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=400&q=80',
                'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?auto=format&fit=crop&w=400&q=80',
                'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=400&q=80',
            ],
            'Women' => [
                'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?auto=format&fit=crop&w=400&q=80',
                'https://images.unsplash.com/photo-1539008835657-9e8e9680c956?auto=format&fit=crop&w=400&q=80',
                'https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?auto=format&fit=crop&w=400&q=80',
                'https://images.unsplash.com/photo-1596783074918-c84cb06531ca?auto=format&fit=crop&w=400&q=80',
                'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?auto=format&fit=crop&w=400&q=80',
            ],
        ];
        
        $images = $imagePool[$category] ?? $imagePool['Men'];
        $index = ($i - 1 + $variant - 1) % count($images);
        return $images[$index];
    }

    private function fakeDescription(string $category): string
    {
        return "High‑quality {$category} apparel. Soft fabric, modern fit, perfect for everyday wear.";
    }
}
