@echo off
setlocal enabledelayedexpansion

echo ================================================
echo   Laravel Ecommerce - Local Setup Script
echo ================================================
echo.

REM Navigate to backend directory
cd /d "%~dp0"

echo [1/6] Checking PHP installation...
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: PHP not found. Please install PHP 8.0+ and add to PATH.
    pause
    exit /b 1
)
php -v | findstr /C:"PHP"
echo.

echo [2/6] Checking Composer installation...
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Composer not found. Please install Composer.
    pause
    exit /b 1
)
echo Composer installed.
echo.

echo [3/6] Installing PHP dependencies...
call composer install
if %errorlevel% neq 0 (
    echo ERROR: Composer install failed.
    pause
    exit /b 1
)
echo.

echo [4/6] Creating MySQL database...
echo.
echo Please ensure MySQL is running (XAMPP/Laragon/standalone MySQL).
echo.
set /p MYSQL_USER="Enter MySQL username (default: root): "
if "%MYSQL_USER%"=="" set MYSQL_USER=root

set /p MYSQL_PASS="Enter MySQL password (press Enter if none): "

echo.
echo Creating database 'ecommerce_db'...
mysql -u %MYSQL_USER% -p%MYSQL_PASS% -e "CREATE DATABASE IF NOT EXISTS ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul
if %errorlevel% neq 0 (
    echo.
    echo WARNING: MySQL command failed. Trying without password...
    mysql -u %MYSQL_USER% -e "CREATE DATABASE IF NOT EXISTS ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    if %errorlevel% neq 0 (
        echo.
        echo ERROR: Could not create database. Please:
        echo   1. Start MySQL (XAMPP Control Panel / Laragon)
        echo   2. Run manually: CREATE DATABASE ecommerce_db;
        echo   3. Then run: php artisan migrate
        pause
        exit /b 1
    )
)
echo Database created successfully!
echo.

echo [5/6] Running migrations...
php artisan config:clear
php artisan migrate --force
if %errorlevel% neq 0 (
    echo ERROR: Migration failed. Check database connection in .env file.
    pause
    exit /b 1
)
echo.

echo [6/6] Seeding database...
php artisan db:seed --class=CategoriesTableSeeder --force
php artisan db:seed --class=AdminUserSeeder --force
echo.
echo Optionally seed demo products (5 each category):
set /p SEED_PRODUCTS="Seed demo products? (y/n): "
if /i "%SEED_PRODUCTS%"=="y" (
    php artisan demo:seed-products --men=5 --women=5 --kids=5
)
echo.

echo ================================================
echo   Setup Complete!
echo ================================================
echo.
echo Next steps:
echo   1. Start backend:  php artisan serve
echo   2. Start frontend: cd ../frontend ^&^& npm install ^&^& npm run dev
echo.
echo Backend will run at:  http://127.0.0.1:8000
echo Frontend will run at: http://localhost:5173
echo.
echo Admin credentials (if seeded):
echo   Email: admin@example.com
echo   Password: password
echo.
pause
