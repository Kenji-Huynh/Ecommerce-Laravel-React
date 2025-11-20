# 🚀 Hướng dẫn Setup Local Development

## Yêu cầu hệ thống

### Backend (Laravel)
- PHP >= 8.0.2
- Composer
- MySQL 5.7+ hoặc MariaDB
- PHP Extensions: `pdo`, `pdo_mysql`, `mbstring`, `curl`, `gd`, `fileinfo`, `tokenizer`, `zip`

### Frontend (React)
- Node.js >= 16.x
- npm hoặc yarn

### Công cụ khuyến nghị
- **XAMPP** (Windows): Bao gồm PHP, MySQL, Apache
- **Laragon** (Windows): Nhẹ hơn, tự động tạo virtual hosts
- **VS Code** với extensions: PHP Intelephense, ESLint, Prettier

---

## 🎯 Cách 1: Setup tự động (Khuyến nghị)

### Bước 1: Chạy script setup
```powershell
cd backend
.\setup-local.bat
```

Script sẽ tự động:
- ✅ Kiểm tra PHP, Composer
- ✅ Cài đặt dependencies
- ✅ Tạo database `ecommerce_db`
- ✅ Chạy migrations
- ✅ Seed categories + admin user
- ✅ (Tùy chọn) Seed demo products

### Bước 2: Khởi động Backend
```powershell
php artisan serve
```
Backend chạy tại: **http://127.0.0.1:8000**

### Bước 3: Khởi động Frontend
```powershell
cd ..\frontend
npm install
npm run dev
```
Frontend chạy tại: **http://localhost:5173**

---

## 🛠️ Cách 2: Setup thủ công

### Backend Setup

#### 1. Cài dependencies
```powershell
cd backend
composer install
```

#### 2. Tạo database MySQL

**Option A: Qua phpMyAdmin (XAMPP)**
1. Mở http://localhost/phpmyadmin
2. Tạo database tên: `ecommerce_db`
3. Collation: `utf8mb4_unicode_ci`

**Option B: MySQL CLI**
```powershell
mysql -u root -p
```
```sql
CREATE DATABASE ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### 3. Cấu hình `.env`
File `.env` đã tồn tại, kiểm tra các giá trị:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=        # Để trống nếu không có password
```

#### 4. Chạy migrations
```powershell
php artisan config:clear
php artisan migrate
```

#### 5. Seed dữ liệu
```powershell
# Categories (bắt buộc)
php artisan db:seed --class=CategoriesTableSeeder

# Admin user (khuyến nghị)
php artisan db:seed --class=AdminUserSeeder

# Demo products (tùy chọn - 5 sản phẩm mỗi category)
php artisan demo:seed-products --men=5 --women=5 --kids=5
```

#### 6. Khởi động server
```powershell
php artisan serve
```

#### 7. Test API
```powershell
curl http://127.0.0.1:8000/api/products
```

### Frontend Setup

#### 1. Cài dependencies
```powershell
cd frontend
npm install
```

#### 2. Cấu hình `.env` (nếu chưa có)
Tạo file `frontend/.env`:
```env
VITE_API_BASE_URL=http://127.0.0.1:8000
```

#### 3. Khởi động dev server
```powershell
npm run dev
```

#### 4. Mở trình duyệt
```
http://localhost:5173
```

---

## 🧪 Kiểm tra hoạt động

### 1. Test đăng ký tài khoản
1. Mở http://localhost:5173
2. Click "Register" / "Đăng ký"
3. Điền form:
   - Name: `Test User`
   - Email: `test@example.com`
   - Password: `password123`
4. Submit → Nếu thành công sẽ redirect về login

### 2. Test đăng nhập
- Email: `test@example.com`
- Password: `password123`

### 3. Test admin (nếu đã seed AdminUserSeeder)
- URL: http://127.0.0.1:8000/admin/login
- Email: `admin@example.com`
- Password: `password`

### 4. Test API endpoints
```powershell
# Lấy danh sách categories
curl http://127.0.0.1:8000/api/categories

# Lấy danh sách products
curl http://127.0.0.1:8000/api/products

# Test health check
curl http://127.0.0.1:8000/api/health
```

---

## ❌ Xử lý lỗi thường gặp

### Lỗi: "SQLSTATE[HY000] [1049] Unknown database 'ecommerce_db'"
**Nguyên nhân:** Database chưa được tạo.

**Giải pháp:**
```powershell
mysql -u root -p -e "CREATE DATABASE ecommerce_db;"
```

### Lỗi: "Access denied for user 'root'@'localhost'"
**Nguyên nhân:** Sai username/password MySQL.

**Giải pháp:**
1. Kiểm tra XAMPP/Laragon đã khởi động MySQL chưa
2. Sửa `DB_USERNAME` và `DB_PASSWORD` trong `.env`
3. Chạy `php artisan config:clear`

### Lỗi: "Class 'PDO' not found"
**Nguyên nhân:** PHP extension `pdo_mysql` chưa bật.

**Giải pháp (XAMPP):**
1. Mở `xampp/php/php.ini`
2. Tìm dòng `;extension=pdo_mysql`
3. Bỏ dấu `;` (uncomment)
4. Restart Apache

### Lỗi: "npm ERR! network" (Frontend)
**Giải pháp:**
```powershell
npm cache clean --force
npm install
```

### Lỗi: "Port 8000 already in use"
**Giải pháp:** Dùng port khác
```powershell
php artisan serve --port=8001
```
Nhớ cập nhật `VITE_API_BASE_URL` trong `frontend/.env`.

---

## 🔄 Reset database (xóa toàn bộ và chạy lại)

```powershell
cd backend
php artisan migrate:fresh --seed
```

Hoặc chỉ reset demo products:
```powershell
php artisan migrate:fresh
php artisan db:seed --class=CategoriesTableSeeder
php artisan db:seed --class=AdminUserSeeder
php artisan demo:seed-products --men=10 --women=10 --kids=10
```

---

## 📝 Tài khoản mẫu sau khi seed

### Admin
- URL: http://127.0.0.1:8000/admin/login
- Email: `admin@example.com`
- Password: `password`

### User thường
Tự tạo qua form đăng ký tại frontend.

---

## 🚀 Các lệnh hữu ích

### Backend
```powershell
# Xóa cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Tạo storage symlink (nếu cần upload file local)
php artisan storage:link

# Chạy queue worker (nếu dùng jobs)
php artisan queue:work

# Generate app key mới
php artisan key:generate

# Rollback migration gần nhất
php artisan migrate:rollback

# Xem tất cả routes
php artisan route:list
```

### Frontend
```powershell
# Build production
npm run build

# Preview production build
npm run preview

# Lint code
npm run lint
```

---

## 📦 Cấu trúc Project

```
Laravel-React-Ecommerce-Project-main/
├── backend/              # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/API/  # API endpoints
│   │   ├── Models/                # Eloquent models
│   │   └── Console/Commands/      # Artisan commands
│   ├── database/
│   │   ├── migrations/            # DB schema
│   │   └── seeders/               # Sample data
│   ├── routes/
│   │   └── api.php                # API routes
│   ├── .env                       # Local config
│   └── setup-local.bat            # Setup script
├── frontend/             # React SPA
│   ├── src/
│   │   ├── components/            # React components
│   │   ├── context/               # Auth, Cart context
│   │   └── services/              # API calls
│   ├── .env                       # Frontend config
│   └── package.json
└── LOCAL_SETUP.md        # This file
```

---

## 🎯 Next Steps sau khi setup local

1. ✅ Test toàn bộ luồng: Đăng ký → Đăng nhập → Xem sản phẩm → Thêm giỏ hàng → Checkout
2. ✅ Test Stripe payment với test cards (4242 4242 4242 4242)
3. ✅ Test admin panel: Quản lý sản phẩm, đơn hàng, users
4. 🚀 Khi đã test ổn, tiến hành deploy production (Railway + Vercel)

---

## 🔗 Tài liệu tham khảo

- [Laravel 9 Docs](https://laravel.com/docs/9.x)
- [React Docs](https://react.dev)
- [Stripe Test Cards](https://stripe.com/docs/testing)
- [Deployment Guide](./RAILWAY_NEON_SETUP.md)
