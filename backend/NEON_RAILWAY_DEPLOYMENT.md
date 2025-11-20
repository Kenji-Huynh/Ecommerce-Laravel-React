# Hướng dẫn Deploy Laravel Backend lên Railway với Neon Database

## 🚀 Tổng quan

**Neon Database** là PostgreSQL serverless với những ưu điểm:
- ✅ **Free tier generous**: 0.5 GB storage, 3 GiB transfer/month
- ✅ **Serverless**: Auto-scaling, chỉ trả tiền khi dùng
- ✅ **Fast**: Branching, instant restore
- ✅ **Easy setup**: Tích hợp dễ dàng với Railway
- ✅ **PostgreSQL**: Hỗ trợ đầy đủ PostgreSQL features

## Bước 1: Tạo Neon Database

1. Truy cập [Neon Console](https://console.neon.tech)
2. Đăng ký/Đăng nhập (có thể dùng GitHub)
3. Click **"Create a project"**
4. Chọn:
   - **Project name**: Laravel Ecommerce
   - **Postgres version**: 16 (latest)
   - **Region**: Chọn gần nhất (Singapore/Tokyo cho Việt Nam)
5. Click **"Create project"**

## Bước 2: Lấy Connection String

Sau khi tạo project, Neon sẽ hiển thị connection details:

```
Connection string:
postgresql://username:password@ep-xxx-xxx.region.aws.neon.tech/neondb?sslmode=require
```

Hoặc lấy từ Dashboard → **Connection Details**:
- **Host**: `ep-xxx-xxx.region.aws.neon.tech`
- **Database**: `neondb`
- **User**: `username`
- **Password**: `your-password`
- **Port**: `5432`

## Bước 3: Deploy lên Railway

### 3.1. Tạo Railway Project

1. Truy cập [Railway Dashboard](https://railway.app/dashboard)
2. Click **"New Project"**
3. Chọn **"Deploy from GitHub repo"**
4. Chọn repository: `Laravel-React-Ecommerce-Project`
5. Railway sẽ tự động detect Laravel project

### 3.2. Cấu hình Root Directory

1. Trong **Settings** của service
2. Tìm **Root Directory**
3. Set value: `backend`
4. Click **Save**

### 3.3. Cấu hình Environment Variables

Trong phần **Variables** của Railway service, thêm các biến sau:

```bash
# App Configuration
APP_NAME="Laravel Ecommerce"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-backend-url.railway.app

# Database - Neon PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=ep-xxx-xxx.region.aws.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=your-neon-username
DB_PASSWORD=your-neon-password

# Optional: Full connection string (Railway có thể dùng cái này)
DATABASE_URL=postgresql://user:pass@ep-xxx.region.aws.neon.tech/neondb?sslmode=require

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

# Laravel Key - Generate với: php artisan key:generate --show
APP_KEY=base64:your_generated_key_here
```

### 3.4. Generate APP_KEY

Có 2 cách:

**Cách 1: Local**
```bash
cd backend
php artisan key:generate --show
```

**Cách 2: Railway CLI**
```bash
railway run php artisan key:generate --show
```

Copy output và set vào `APP_KEY` environment variable.

## Bước 4: Deploy & Migration

### 4.1. Trigger Deployment

Railway sẽ tự động deploy sau khi bạn:
1. Set xong Environment Variables
2. Hoặc click **"Deploy"** để manual trigger

### 4.2. Xem Logs

Trong **Deployments** tab, click vào deployment hiện tại để xem logs:
- ✅ Kiểm tra build thành công
- ✅ Migrations đã chạy (tự động chạy qua Procfile)
- ✅ Server đã start

## Bước 5: Seed Database (Optional)

Nếu muốn seed sample data:

**Railway CLI:**
```bash
railway run php artisan db:seed --force
```

**Hoặc vào Railway Dashboard:**
1. Click vào deployment
2. Mở **Terminal**
3. Chạy: `php artisan db:seed --force`

## So sánh: Neon vs Railway MySQL

| Feature | Neon (PostgreSQL) | Railway MySQL |
|---------|-------------------|---------------|
| **Free Tier** | 0.5 GB, 3 GiB transfer | $5/month credit |
| **Scaling** | Auto-scaling | Manual |
| **Performance** | Serverless, fast | Good |
| **Branching** | ✅ Database branching | ❌ |
| **Setup** | External service | Built-in |
| **Latency** | Depends on region | Same as Railway |

## 🎯 Khuyến nghị

**Dùng Neon nếu:**
- ✅ Muốn free tier dài hạn
- ✅ Cần database branching (dev/staging/prod)
- ✅ PostgreSQL features (JSON, full-text search)
- ✅ Auto-scaling

**Dùng Railway MySQL nếu:**
- ✅ Muốn setup đơn giản hơn (all-in-one)
- ✅ Code đã tối ưu cho MySQL
- ✅ Không muốn manage external service

## Lưu ý quan trọng

### 1. PostgreSQL vs MySQL Differences

Một số query có thể khác nhau:

**String concatenation:**
```php
// MySQL
DB::raw("CONCAT(first_name, ' ', last_name)")

// PostgreSQL (vẫn work)
DB::raw("CONCAT(first_name, ' ', last_name)")
// Hoặc
DB::raw("first_name || ' ' || last_name")
```

**Auto-increment:**
- MySQL: `AUTO_INCREMENT`
- PostgreSQL: `SERIAL` hoặc `BIGSERIAL` (Laravel tự động handle)

### 2. Migration Compatibility

Laravel migrations của bạn đã compatible với PostgreSQL! ✅
Không cần thay đổi gì vì Laravel abstracts database differences.

### 3. SSL Connection

Neon yêu cầu SSL. Config đã được set sẵn trong `config/database.php`:
```php
'pgsql' => [
    'sslmode' => 'prefer', // Auto-handle SSL
]
```

## Troubleshooting

### Lỗi "could not connect to server"
- ✅ Kiểm tra `DB_HOST` đúng chưa
- ✅ Kiểm tra `DB_PASSWORD` không có ký tự đặc biệt cần escape
- ✅ Thử dùng `DATABASE_URL` thay vì separate variables

### Lỗi "SSL connection required"
- ✅ Đảm bảo connection string có `?sslmode=require`
- ✅ Hoặc set trong .env: `DB_SSLMODE=require`

### Lỗi Migration
```bash
# Clear cache và retry
railway run php artisan config:clear
railway run php artisan migrate:fresh --force
```

## Testing Connection

Sau khi deploy, test API endpoints:

```bash
# Health check
curl https://your-backend-url.railway.app/api/categories

# Database check
curl https://your-backend-url.railway.app/api/products
```

## Railway CLI Commands (Useful)

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link to project
railway link

# Run commands
railway run php artisan migrate
railway run php artisan db:seed
railway run php artisan tinker

# View logs
railway logs

# Open project
railway open
```

## Kết luận

✅ **Neon + Railway** là combo rất tốt cho production:
- Neon handle database (serverless, auto-scaling)
- Railway handle application (easy deploy, CI/CD)
- Both có free tier generous
- PostgreSQL powerful hơn MySQL cho ecommerce

**Next steps:**
1. ✅ Setup Neon database
2. ✅ Deploy backend lên Railway
3. ✅ Test API endpoints
4. ✅ Deploy frontend lên Vercel
5. ✅ Update `VITE_API_URL` trong frontend

Good luck! 🚀
