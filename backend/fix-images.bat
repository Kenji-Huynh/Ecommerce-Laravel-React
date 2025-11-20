@echo off
echo Updating product images with working Unsplash URLs...

php artisan tinker --execute="
$images = [
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

foreach (['Kids', 'Men', 'Women'] as \$catName) {
    \$category = \App\Models\Category::where('slug', strtolower(\$catName))->first();
    if (!\$category) continue;
    
    \$products = \App\Models\Product::where('category_id', \$category->id)->get();
    \$i = 0;
    foreach (\$products as \$product) {
        \$imgIndex = \$i % count(\$images[\$catName]);
        \$product->main_image = \$images[\$catName][\$imgIndex];
        \$product->save();
        echo \"Updated: {\$product->name} -> {\$product->main_image}\n\";
        \$i++;
    }
}
echo \"✅ Done updating images!\";
"

echo.
echo Press any key to close...
pause >nul
