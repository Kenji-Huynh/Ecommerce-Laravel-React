#!/bin/sh
set -e

echo "🚀 Starting Laravel Ecommerce Backend..."

# 1. Create all required directories
echo "📁 Creating required directories..."
mkdir -p bootstrap/cache
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p storage/app/public

# 2. Discover packages (now that .env is available)
echo "📦 Discovering packages..."
php -v || true
pwd || true
ls -la || true
ls -la bootstrap || true
ls -la storage || true
ls -la storage/framework || true
ls -la storage/framework/views || true
ls -la resources || true
ls -la resources/views || true
php artisan package:discover --ansi || echo "⚠️  Package discovery failed (continuing)"

# 3. Clear caches to avoid stale http scheme in compiled views/routes
echo "🧹 Clearing caches (config/route/view)..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "⚡ Caching config..."
php artisan config:cache || echo "⚠️  Config cache failed (continuing)"

# 4. Run database migrations (allow failure)
if [ "${DB_FRESH_MIGRATE}" = "true" ]; then
	echo "🗄️  Running FRESH migrations (drops all tables)..."
	php artisan migrate:fresh --force || echo "⚠️  Migration failed (continuing anyway)"
elif [ "${RUN_MIGRATIONS_ON_START}" = "true" ]; then
	echo "🗄️  Running database migrations..."
	php artisan migrate --force || echo "⚠️  Migration failed (continuing anyway)"
else
	echo "⏭️  Skipping migrations (set RUN_MIGRATIONS_ON_START=true to enable)"
fi

if [ "${RUN_SEED_ON_START}" = "true" ]; then
	echo "🌱 Seeding database (Categories)..."
	php artisan db:seed --class=CategoriesTableSeeder --force || echo "⚠️  Categories seed skipped"
else
	echo "⏭️  Skipping seeding (set RUN_SEED_ON_START=true to enable)"
fi
php artisan db:seed --class=ProductsTableSeeder --force || echo "⚠️  Products seed skipped"

# 6. Start PHP development server
echo "✅ Server starting on 0.0.0.0:${PORT:-8080}"
php -S 0.0.0.0:${PORT:-8080} -t public
