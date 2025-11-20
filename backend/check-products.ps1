# Quick check product images
php artisan tinker --execute="echo json_encode(\App\Models\Product::select('id', 'name', 'main_image')->get()->toArray(), JSON_PRETTY_PRINT);"
