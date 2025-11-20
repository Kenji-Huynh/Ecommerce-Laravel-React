# Pure Wear E-commerce (Laravel + React)

A full-stack fashion e-commerce application featuring a Laravel 10 (PHP 8) backend and a React (Vite) frontend. Includes product catalog, cart, checkout scaffold (Stripe integration), user accounts with orders & password management, and an admin panel optimized for mobile (off‑canvas sidebar + quick bottom nav).

## Tech Stack
- Backend: Laravel 10, PHP 8+, Sanctum auth, Stripe PHP SDK
- Frontend: React 19 + Vite, React Router, Axios, Bootstrap 5, FontAwesome, React Toastify, Stripe JS
- Build & Deploy: Railway (backend), Neon PostgreSQL (recommended) or MySQL, Vercel (frontend)
- Dev Tools: ESLint, SweetAlert2

## Core Features
- Product listing, detail pages with images & badges (new/sale)
- Shopping cart & secure checkout skeleton (Stripe ready)
- User authentication (login/register) + protected routes
- Account area: order list, order detail, change password
- Admin dashboard: manage products, orders, users
- Responsive mobile admin UI: hamburger off‑canvas sidebar + persistent quick bottom navigation bar
- Accessibility improvements (focus trap on mobile sidebar, Escape to close)

## Project Structure
```
backend/    # Laravel application
frontend/   # React Vite SPA
```
Separate environment variables for backend & frontend.

## Local Development
### Prerequisites
- PHP 8.0.2+, Composer
- Node.js 18+ (16+ works but recommend current LTS)
- MySQL OR PostgreSQL (see database section below)

### Fast Setup (Scripts)
Backend contains `LOCAL_SETUP.md` and `setup-local.bat` to automate install & seed.

### Manual Steps
```powershell
# Backend
cd backend
composer install
cp .env.example .env  # If not yet created
php artisan key:generate
# Configure DB credentials in .env then:
php artisan migrate --seed
php artisan serve  # http://127.0.0.1:8000

# (Optional) Demo products
php artisan demo:seed-products --men=5 --women=5 --kids=5

# Frontend
cd ../frontend
npm install
npm run dev  # http://localhost:5173
```
Set `frontend/.env`:
```
VITE_API_URL=http://127.0.0.1:8000
VITE_STRIPE_PUBLISHABLE_KEY=pk_test_...
```

## Environment Variables (Backend)
Key variables (Laravel `.env`):
- `APP_URL` – Base URL
- `FRONTEND_URL` – CORS allowed origin (e.g. https://your-frontend.vercel.app)
- `DB_CONNECTION=mysql|pgsql`
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `STRIPE_SECRET` – (when enabling live Stripe server-side)

## Environment Variables (Frontend)
- `VITE_API_URL` – Points to backend API
- `VITE_STRIPE_PUBLISHABLE_KEY` – Stripe publishable key
- `VITE_PRICE_CONVERT_VND_TO_USD_RATE` – Optional conversion rate placeholder

## Database Choice & Recommendation
The project currently uses MySQL semantics in examples, but is portable.

| Criteria              | MySQL / MariaDB                  | PostgreSQL (Neon)                              |
|-----------------------|----------------------------------|-----------------------------------------------|
| JSON / Advanced Types | Basic JSON, no array types       | Rich JSONB, arrays, full text (tsvector)      |
| Full Text / Search    | Available, less flexible         | Powerful & extensible                         |
| Migrations            | Works out of the box             | Works; minor tweaks if using MySQL-specific types |
| Scaling (Serverless)  | PlanetScale (MySQL) optional     | Neon serverless autoscaling highly convenient |
| Concurrency / Locks   | Good                             | Robust (MVCC)                                 |
| Extensions            | Limited                          | Many (UUID, pgcrypto, fuzzy search, etc.)     |

**Recommendation:** Use **Neon PostgreSQL** for cloud deployment on Railway:
- Serverless autoscaling & branching for previews.
- Better future flexibility (search, analytics, JSONB indexing).
- Smooth SSL connection; Railway + Neon documented steps already present (`RAILWAY_NEON_SETUP.md`).

### Switching From MySQL to PostgreSQL
1. Update `.env`:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=your-neon-host.neon.tech
   DB_PORT=5432
   DB_DATABASE=neondb
   DB_USERNAME=neondb_owner
   DB_PASSWORD=XXXX
   ```
2. Ensure no MySQL-specific column types (e.g. `unsigned*`). Laravel abstracts most; adjust if necessary.
3. Run fresh migrations:
   ```powershell
   php artisan migrate:fresh --seed
   ```
4. Test critical flows (auth, products, orders) for any SQL driver differences.

## GitHub Repository Setup
From project root (`d:\Laravel-React-Ecommerce-Project-main`):
```powershell
# Initialize (if not already a repo)
git init

# Add all content
git add .

git commit -m "Initial commit: Pure Wear E-commerce (Laravel + React)"

# Set main branch
git branch -M main

# Add remote (replace YOUR_USERNAME & REPO_NAME)
git remote add origin https://github.com/YOUR_USERNAME/REPO_NAME.git

# Push
git push -u origin main
```
If repository already exists, skip `git init` and adjust remote URL.

## Deployment Overview
### Backend (Railway + Neon PostgreSQL)
1. Create Neon project → obtain connection details.
2. Create Railway project → connect GitHub repo.
3. Set Railway Variables using template in `backend/RAILWAY_ENV_TEMPLATE.txt` or `RAILWAY_NEON_SETUP.md`.
4. Ensure `APP_URL` and `FRONTEND_URL` match deployed domains.
5. Trigger deploy; on first build run migrations:
   ```powershell
   railway run php artisan migrate --force
   railway run php artisan db:seed --class=AdminUserSeeder
   ```

### Frontend (Vercel)
1. Import GitHub repo in Vercel.
2. Framework preset: Vite.
3. Set Environment Variables (`VITE_API_URL`, `VITE_STRIPE_PUBLISHABLE_KEY`).
4. Deploy → test API calls & CORS.

### CORS Configuration
In `backend/config/cors.php` ensure allowed origins include the deployed frontend URL + localhost.

### Stripe Activation
Backend currently uses client-side Stripe integration scaffold. For production charges, add server endpoints (PaymentIntent creation) and set `STRIPE_SECRET`.

## Admin Mobile UI Notes
- Sidebar transforms with `translate3d` for smooth GPU acceleration.
- Focus trap ensures accessibility when sidebar open.
- Bottom quick nav provides primary section shortcuts & toggle for menu.
- Main content has extra bottom padding to avoid being covered.

## Testing & Quality
- Backend: Use `php artisan test` for feature/unit tests (extend as needed).
- Frontend: Add React Testing Library / Vitest (not yet included) for components.
- Lint: `npm run lint` in frontend.

## Future Enhancements
- Product search & filtering (Elasticsearch or PostgreSQL full text)
- Image CDN (Cloudinary integration already hinted by vendor packages)
- Payment workflow finalization (Stripe PaymentIntent server-side)
- Inventory & stock management
- Admin role/permission system (Laravel Gates/Policies)
- Vitest + React Testing Library for frontend coverage

## Troubleshooting Quick Tips
- 419 errors: ensure `axios.defaults.withCredentials = true` and CORS settings.
- CORS: double-check `FRONTEND_URL` and `allowed_origins` list.
- DB connection errors: verify SSL mode for Neon (add `?sslmode=require` if using raw DSN).

## License
No explicit license included. Add one (MIT / Apache-2.0) if open-sourcing.

---
**Recommendation Recap:** Deploy with Neon PostgreSQL + Railway for backend; Vercel for frontend. Maintain `.env` separation and keep secrets out of version control.
