# Quick fix: Update all products with working Unsplash images
$images = @{
    'kids' = @(
        'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1622290291468-a28f7a7dc238?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1514090458221-65bb69cf63e6?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1519689373023-dd07c7988603?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1560114928-40f1f1eb26a0?auto=format&fit=crop&w=400&q=80'
    )
    'men' = @(
        'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=400&q=80'
    )
    'women' = @(
        'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1539008835657-9e8e9680c956?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1596783074918-c84cb06531ca?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?auto=format&fit=crop&w=400&q=80'
    )
}

Write-Host "Updating product images..." -ForegroundColor Yellow

# Update Kids products
$kidsProducts = php artisan tinker --execute="echo json_encode(App\Models\Product::whereHas('category', function(`$q) { `$q->where('slug', 'kids'); })->pluck('id')->toArray());"
$kidsIds = $kidsProducts | ConvertFrom-Json
$i = 0
foreach ($id in $kidsIds) {
    $img = $images['kids'][$i % $images['kids'].Count]
    php artisan tinker --execute="App\Models\Product::find($id)->update(['main_image' => '$img']);"
    Write-Host "  Updated Kids Product $id" -ForegroundColor Green
    $i++
}

# Update Men products
$menProducts = php artisan tinker --execute="echo json_encode(App\Models\Product::whereHas('category', function(`$q) { `$q->where('slug', 'men'); })->pluck('id')->toArray());"
$menIds = $menProducts | ConvertFrom-Json
$i = 0
foreach ($id in $menIds) {
    $img = $images['men'][$i % $images['men'].Count]
    php artisan tinker --execute="App\Models\Product::find($id)->update(['main_image' => '$img']);"
    Write-Host "  Updated Men Product $id" -ForegroundColor Green
    $i++
}

# Update Women products
$womenProducts = php artisan tinker --execute="echo json_encode(App\Models\Product::whereHas('category', function(`$q) { `$q->where('slug', 'women'); })->pluck('id')->toArray());"
$womenIds = $womenProducts | ConvertFrom-Json
$i = 0
foreach ($id in $womenIds) {
    $img = $images['women'][$i % $images['women'].Count]
    php artisan tinker --execute="App\Models\Product::find($id)->update(['main_image' => '$img']);"
    Write-Host "  Updated Women Product $id" -ForegroundColor Green
    $i++
}

Write-Host "`n✅ All product images updated!" -ForegroundColor Cyan
