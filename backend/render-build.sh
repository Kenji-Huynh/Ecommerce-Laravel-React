#!/bin/sh
set -e

echo "🚀 Starting Render build process..."

# Run composer install
echo "📦 Installing PHP dependencies..."
COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader

# Create required directories
echo "📁 Creating Laravel storage directories..."
mkdir -p bootstrap/cache
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p storage/app/public

# Set permissions
chmod -R 775 storage bootstrap/cache

# Run Laravel optimization
echo "⚡ Running Laravel optimizations..."
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run migrations (optional - uncomment if you want auto-migration)
# echo "🗄️  Running database migrations..."
# php artisan migrate --force

echo "✅ Build completed successfully!"
