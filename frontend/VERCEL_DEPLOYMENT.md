# Hướng dẫn Deploy Frontend (React + Vite) lên Vercel

## 🚀 Bước 1: Chuẩn bị

### URL Backend Railway của bạn:
```
https://laravel-react-ecommerce-project-production.railway.app
```

## 📝 Bước 2: Tạo file Environment Variables cho Vercel

Tạo file `.env.production` trong thư mục `frontend/`:

```bash
VITE_API_URL=https://laravel-react-ecommerce-project-production.railway.app
VITE_STRIPE_PUBLISHABLE_KEY=pk_test_51SQAiXK5MnE3hyDDBb3jKRUZSZrmtEj7LM4oAg4gac0rtR2vRm0oWA7nKjOJFdvdm8pt59djpxPm7Yfv5SNi6KeR00GE4oCUQO
VITE_PRICE_CONVERT_VND_TO_USD_RATE=
```

## 🌐 Bước 3: Deploy lên Vercel

### Cách 1: Vercel Dashboard (Đơn giản nhất)

1. Vào https://vercel.com
2. Đăng nhập bằng GitHub
3. Click **"Add New"** → **"Project"**
4. Chọn repository: **Laravel-React-Ecommerce-Project**
5. Configure Project:
   - **Framework Preset**: Vite
   - **Root Directory**: `frontend`
   - **Build Command**: `npm run build` (hoặc để mặc định)
   - **Output Directory**: `dist` (hoặc để mặc định)

6. **Environment Variables** - Add 3 biến:
   ```
   VITE_API_URL = https://laravel-react-ecommerce-project-production.railway.app
   VITE_STRIPE_PUBLISHABLE_KEY = pk_test_51SQAiXK5MnE3hyDDBb3jKRUZSZrmtEj7LM4oAg4gac0rtR2vRm0oWA7nKjOJFdvdm8pt59djpxPm7Yfv5SNi6KeR00GE4oCUQO
   VITE_PRICE_CONVERT_VND_TO_USD_RATE = (để trống)
   ```

7. Click **"Deploy"**

### Cách 2: Vercel CLI

```bash
# Install Vercel CLI
npm i -g vercel

# Login
vercel login

# Deploy
cd frontend
vercel

# Follow prompts:
# - Link to existing project? No
# - Project name: laravel-react-ecommerce-frontend
# - Directory: ./
# - Build command: npm run build
# - Output directory: dist
```

## 🔧 Bước 4: Cập nhật CORS trong Backend

Sau khi frontend được deploy, Vercel sẽ cho bạn URL (ví dụ: `https://your-app.vercel.app`)

### Update Railway Environment Variables:

1. Vào Railway Dashboard
2. Your Service → **Variables**
3. Update biến `FRONTEND_URL`:
   ```
   FRONTEND_URL=https://your-app.vercel.app
   ```
4. Railway sẽ tự động redeploy

## ✅ Bước 5: Test

1. Mở frontend URL: `https://your-app.vercel.app`
2. Test đăng ký/đăng nhập
3. Test các chức năng khác

## 🐛 Troubleshooting

### Lỗi CORS vẫn còn:

Check `backend/config/cors.php`:
```php
'allowed_origins' => [
    env('FRONTEND_URL', 'http://localhost:5173'),
    'http://localhost:5173',
],
```

### Lỗi 419 Page Expired:

Đảm bảo frontend gọi API với credentials:
```javascript
// services/api.js
axios.defaults.withCredentials = true;
```

### Lỗi API không load:

Check `VITE_API_URL` trong Vercel Environment Variables có đúng không.

## 📋 Checklist:

- [ ] Frontend deploy lên Vercel thành công
- [ ] Environment variables đã set đúng
- [ ] Backend Railway variable `FRONTEND_URL` đã update
- [ ] Test đăng ký/đăng nhập OK
- [ ] Test API calls OK

Good luck! 🚀
