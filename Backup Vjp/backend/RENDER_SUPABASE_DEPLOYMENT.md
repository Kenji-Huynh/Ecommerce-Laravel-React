# Deploy Backend on Render + Database on Supabase

This guide helps you deploy the Laravel backend on Render, use Supabase (PostgreSQL) for the database, and point your Vercel frontend at the backend.

## 1) Supabase: Create Postgres and get credentials

1. Go to https://supabase.com → New project
2. Choose org → Name → Region (close to your users)
3. Set database password → Create project
4. In Project Settings → Database → Connection info, copy:
   - Host, Port, Database, User, Password
   - Or copy the full `Connection string` and add `?sslmode=require`

Notes: Supabase requires SSL from external hosts. Keep `sslmode=require`.

## 2) Render: Create Web Service for Laravel backend

1. Push this repo to GitHub
2. On https://render.com → New → Web Service → Connect repo
3. Root directory: `backend/`
4. Runtime: Nixpacks (auto-detected via `nixpacks.toml`)
5. Build command: (leave default) or set to `./render-build.sh`
6. Start command: (leave default) `sh start-server.sh` (provided in repo)
7. Add environment variables (use the template `RENDER_ENV_TEMPLATE_SUPABASE.txt`):
   - `APP_URL` → your Render service URL (https)
   - Database (choose one):
     - `DATABASE_URL=postgresql://USER:PASSWORD@HOST:5432/DB?sslmode=require`, or
     - `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT=5432`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_SSLMODE=require`
   - `FRONTEND_URL` → your Vercel URL (e.g. `https://your-frontend.vercel.app`)
   - Stripe keys if you use payments

After first deploy, open a Render Shell and run:

```
php artisan migrate --force
php artisan db:seed --force   # optional if you want demo data
```

If you prefer auto-migration on boot, set `RUN_MIGRATIONS_ON_START=true` (see `start-server.sh`).

## 3) Vercel: Point frontend to backend

In your frontend project settings on Vercel, set:

- `VITE_API_URL` = your Render backend URL (e.g. `https://your-backend.onrender.com`)
- `VITE_STRIPE_PUBLISHABLE_KEY` = your Stripe publishable key (if payments enabled)

Redeploy the frontend.

## 4) CORS and Auth

- Backend `config/cors.php` already allows:
  - Specific `FRONTEND_URL`
  - Patterns for `*.vercel.app`
- Frontend uses bearer tokens (cookies disabled in `services/api.js`). No Sanctum cookie config needed.

## 5) Alternative: Render Managed PostgreSQL

If you prefer everything on Render:

1. Create a Render PostgreSQL instance
2. Copy its connection string/parameters
3. In your Web Service env vars, set either `DATABASE_URL` or the `DB_*` variables
4. Keep `DB_SSLMODE=require` if the instance enforces SSL

## 6) Local development tips

- Local: keep MySQL (`.env` default) or switch to Postgres by setting `DB_CONNECTION=pgsql` and running migrations
- Seeding demo products: use provided Artisan commands or seeders
- Image uploads: project integrates Cloudinary; no persistent disk required on Render

## 7) Checklist

- [ ] Supabase DB created, credentials noted
- [ ] Render Web Service deployed from `backend/`
- [ ] Env vars set (APP_URL, DB vars, FRONTEND_URL, Stripe)
- [ ] `php artisan migrate --force` executed on Render
- [ ] Frontend `VITE_API_URL` set on Vercel

Done! Your stack is now: Vercel (frontend) → Render (Laravel API) → Supabase (Postgres).
