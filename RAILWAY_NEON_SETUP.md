# 🚀 Hướng dẫn Deploy Backend Laravel lên Railway với Neon PostgreSQL

## Bước 1: Chuẩn bị Neon Database

### 1.1 Tạo Neon Database (Nếu chưa có)
1. Truy cập [Neon Console](https://console.neon.tech)
2. Đăng ký/Đăng nhập (dùng GitHub)
3. Click **"Create a project"**
4. Đặt tên: `Laravel Ecommerce`
5. Chọn region: Singapore/Tokyo (gần Việt Nam)
6. Click **"Create project"**

### 1.2 Lấy Connection Details
Sau khi tạo project, copy các thông tin sau:
- **Host**: `ep-xxx-xxx.region.aws.neon.tech`
- **Database**: `neondb`
- **Username**: `neondb_owner` 
- **Password**: `npg_xxxxx`
- **Connection String**: `postgresql://username:password@host:5432/neondb?sslmode=require`

## Bước 2: Tạo Project trên Railway

### 2.1 Đăng nhập Railway
1. Truy cập [Railway.app](https://railway.app)
2. Đăng nhập bằng GitHub
3. Click **"New Project"**

### 2.2 Deploy từ GitHub
1. Chọn **"Deploy from GitHub repo"**
2. Chọn repo: `Laravel-React-Ecommerce-Project`
3. Railway sẽ tự detect Laravel project
4. Đợi build xong (khoảng 3-5 phút)

## Bước 3: Cấu hình Environment Variables

### 3.1 Mở Railway Variables
1. Vào project → Click service backend
2. Tab **"Variables"** → **"RAW Editor"**
3. Copy & paste toàn bộ nội dung dưới đây:

### 3.2 Environment Variables Template
```bash
# ===================================================================
# RAILWAY ENVIRONMENT VARIABLES - PRODUCTION
# ===================================================================

# --- App Configuration ---
APP_NAME=Laravel Ecommerce
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-backend-url.up.railway.app
APP_KEY=base64:5Q3ZApHhK7A9+4f3hFnIiLOcwZoLp3FqvvO1lGiNj40=

# --- Logging ---
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# --- Database - Neon PostgreSQL ---
DB_CONNECTION=pgsql
DB_HOST=your-neon-host.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=neondb_owner
DB_PASSWORD=your-neon-password

# --- Cache & Session ---
BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# --- Mail Configuration ---
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME=Laravel

# --- CORS - Frontend URL ---
FRONTEND_URL=https://your-frontend.vercel.app

# --- Stripe Payment (Nếu sử dụng) ---
STRIPE_KEY=pk_test_your_stripe_public_key
STRIPE_SECRET=sk_test_your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

### 3.3 Thay thế các giá trị cần thiết:
**QUAN TRỌNG**: Thay thế các giá trị sau:

1. **APP_URL**: 
   - Sau khi Railway deploy xong, copy URL từ Railway dashboard
   - Ví dụ: `https://laravel-backend-production-abc123.up.railway.app`

2. **DB_HOST, DB_PASSWORD**: 
   - Thay bằng thông tin Neon của bạn
   - Copy từ Neon Dashboard → Connection Details

3. **FRONTEND_URL**: 
   - URL frontend Vercel của bạn
   - Ví dụ: `https://your-app.vercel.app`

## Bước 4: Kiểm tra Deploy

### 4.1 Xem Logs
1. Tab **"Deployments"** → Click deployment mới nhất
2. Xem logs để đảm bảo không có lỗi
3. Đợi status = **"Success"**

### 4.2 Test Database Connection
1. Mở URL Railway backend
2. Vào `https://your-backend.up.railway.app/admin/login`
3. Nếu không có lỗi database → Kết nối thành công

### 4.3 Chạy Migration (Nếu cần)
Nếu tables chưa được tạo:
1. Railway → Service → **"Deploy"** tab
2. Logs sẽ hiện migration tự chạy
3. Hoặc manual: vào **"Settings"** → **"Custom Start Command"**

## Bước 5: Troubleshooting

### 5.1 Lỗi Database Connection
```
SQLSTATE[08006] [7] Connection refused
```
**Giải pháp**:
- Kiểm tra DB_HOST, DB_PASSWORD trong Railway Variables
- Đảm bảo Neon database đang running
- Check Neon IP whitelist (thường không cần với Railway)

### 5.2 Lỗi APP_KEY
```
No application encryption key has been specified
```
**Giải pháp**:
- Generate key mới: `php artisan key:generate --show`
- Thêm vào Railway Variables: `APP_KEY=base64:xxxxx`

### 5.3 Lỗi HTTPS/Mixed Content
```
Mixed Content: The page was loaded over HTTPS, but requested an insecure resource
```
**Giải pháp**:
- Đảm bảo APP_URL bắt đầu bằng `https://`
- Check FRONTEND_URL cũng là `https://`
- Redeploy sau khi sửa

## Bước 6: Tối ưu Production

### 6.1 Tắt Debug
```bash
APP_DEBUG=false
LOG_LEVEL=warning
```

### 6.2 Tối ưu Database
- Neon có connection pooling tự động
- Set `DB_POOL=true` nếu cần
- Monitor usage trên Neon dashboard

### 6.3 Set CORS đúng
```bash
FRONTEND_URL=https://exact-frontend-domain.vercel.app
```

## Bước 7: Next Steps

### 7.1 Deploy Frontend
1. Frontend deploy trên Vercel
2. Set env variable: `VITE_API_URL=https://your-railway-backend.up.railway.app`

### 7.2 Testing End-to-End
1. Test login/register từ frontend
2. Test API calls
3. Check CORS hoạt động
4. Verify database operations

---

## 📞 Support

Nếu gặp lỗi:
1. Check Railway logs tab
2. Verify Neon connection
3. Ensure all URLs use https://
4. Clear browser cache and hard refresh

**Successful deploy indicators:**
- ✅ Railway build status: Success
- ✅ Backend URL accessible
- ✅ Admin login page loads
- ✅ Database queries work
- ✅ Frontend can call APIs