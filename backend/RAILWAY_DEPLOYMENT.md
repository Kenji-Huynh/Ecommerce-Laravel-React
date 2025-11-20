# Hướng dẫn Deploy Laravel Backend lên Railway

## Bước 1: Chuẩn bị

1. Đảm bảo code đã được push lên GitHub
2. Tạo tài khoản tại [Railway.app](https://railway.app)
3. Đăng nhập Railway bằng tài khoản GitHub

## Bước 2: Tạo Project mới trên Railway

1. Truy cập [Railway Dashboard](https://railway.app/dashboard)
2. Click **"New Project"**
3. Chọn **"Deploy from GitHub repo"**
4. Chọn repository của bạn: `Ecommerce-React-Laravel-Project`
5. Railway sẽ tự động detect Laravel project

## Bước 3: Cấu hình Database (MySQL)

1. Trong Railway project, click **"New"** → **"Database"** → **"Add MySQL"**
2. Railway sẽ tự động tạo MySQL database và cung cấp connection strings
3. Copy các biến môi trường từ MySQL service

## Bước 4: Cấu hình Environment Variables

Trong phần **Variables** của service backend, thêm các biến sau:

```bash
# App Configuration
APP_NAME="Your E-commerce App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-backend-url.railway.app

# Database (Railway sẽ tự động inject các biến này từ MySQL service)
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# Stripe Configuration
STRIPE_SECRET=sk_test_your_stripe_secret_key
STRIPE_CURRENCY=usd
PRICE_CONVERT_VND_TO_USD_RATE=0.00004

# CORS - Frontend URL
FRONTEND_URL=https://your-frontend-url.vercel.app

# Laravel Key (Railway sẽ tự generate, hoặc bạn có thể dùng: php artisan key:generate --show)
APP_KEY=base64:your_generated_key_here
```

## Bước 5: Cấu hình Root Directory

1. Trong **Settings** của service
2. Tìm **Root Directory**
3. Set value: `backend`
4. Click **Save**

## Bước 6: Deploy

1. Railway sẽ tự động deploy sau khi bạn cấu hình xong
2. Hoặc click **"Deploy"** để trigger manual deployment
3. Xem logs để theo dõi quá trình deploy

## Bước 7: Generate Application Key (Nếu cần)

Nếu APP_KEY chưa có, bạn có thể generate bằng cách:

1. Vào phần **Deployments** → Click vào deployment hiện tại
2. Mở **Terminal** (hoặc dùng Railway CLI)
3. Chạy: `php artisan key:generate --show`
4. Copy key và thêm vào Environment Variables

## Bước 8: Chạy Migration

Railway sẽ tự động chạy migration khi deploy (đã config trong Procfile).
Nếu cần chạy manual:

```bash
php artisan migrate --force
```

## Bước 9: Seed Database (Optional)

Nếu muốn seed sample data:

```bash
php artisan db:seed --force
```

## Bước 10: Cấu hình CORS

Đảm bảo file `config/cors.php` cho phép frontend domain:

```php
'allowed_origins' => [
    env('FRONTEND_URL', 'http://localhost:5173'),
],
```

## Bước 11: Test API

1. Copy URL của backend service: `https://your-backend-url.railway.app`
2. Test endpoint: `https://your-backend-url.railway.app/api/categories`
3. Cập nhật `VITE_API_URL` trong frontend để trỏ đến backend Railway

## Lưu ý quan trọng

- ✅ Railway sẽ tự động detect và sử dụng `nixpacks.toml` để build
- ✅ Procfile và railway.json đã được config sẵn
- ✅ Migration sẽ tự động chạy khi deploy
- ⚠️ Đảm bảo APP_DEBUG=false trong production
- ⚠️ Thay đổi STRIPE_SECRET thành production key khi ready
- ⚠️ Cấu hình FRONTEND_URL đúng để CORS hoạt động

## Troubleshooting

### Lỗi "No application encryption key"
- Generate key: `php artisan key:generate --show`
- Add vào Environment Variables: `APP_KEY=base64:...`

### Lỗi Database connection
- Kiểm tra MySQL service đã được link chưa
- Verify các biến DB_* đã đúng chưa

### Lỗi CORS
- Kiểm tra FRONTEND_URL trong .env
- Verify config/cors.php

## Railway CLI (Optional)

Install Railway CLI để deploy và debug dễ hơn:

```bash
npm i -g @railway/cli
railway login
railway link
railway up
```

## Monitoring

- Xem logs real-time trong Railway Dashboard
- Set up alerts cho errors
- Monitor database usage và performance
