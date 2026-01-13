@echo off
REM 🚀 Script Setup Railway Deployment (Windows)
REM Automatisasi setup environment variables dan deployment

echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║       Railway Deployment Setup Helper                  ║
echo ║       TikTok Analysis Application (Windows)             ║
echo ╚════════════════════════════════════════════════════════╝
echo.

REM Check if Railway CLI is installed
where railway >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ Railway CLI tidak ditemukan!
    echo.
    echo 📝 Install Railway CLI:
    echo    npm install -g @railway/cli
    echo.
    pause
    exit /b 1
)

echo ✅ Railway CLI ditemukan
echo.

REM Step 1: Login
echo 📝 Step 1: Login ke Railway...
railway login
echo ✅ Login berhasil
echo.

REM Step 2: Link project
echo 📝 Step 2: Link ke Railway project...
echo    Pilih project TikTok Analysis saat diminta
railway link
echo ✅ Project linked
echo.

REM Step 3: Generate APP_KEY
echo 📝 Step 3: Generate APP_KEY...
REM Check if APP_KEY exists in .env.production
findstr /i "APP_KEY=" .env.production >nul 2>nul
if %errorlevel% neq 0 (
    echo    Silakan update APP_KEY di .env.production
) else (
    echo    APP_KEY sudah ada di .env.production
)
echo.

REM Step 4: Instructions
echo 📝 Step 4: Setup Environment Variables di Railway Dashboard
echo.
echo    Buka: https://railway.app
echo    Project: TikTok Analysis
echo    Tab: Variables
echo.
echo    Tambahkan/Update:
echo    - APP_ENV=production
echo    - APP_DEBUG=false
echo    - APP_KEY=base64:YOUR_KEY
echo    - LOG_LEVEL=warning
echo    - SESSION_DRIVER=database
echo    - CACHE_STORE=database
echo    - QUEUE_CONNECTION=database
echo.

REM Step 5: Database
echo ⚠️  PENTING: Setup Database terlebih dahulu!
echo.
echo    Opsi 1: MySQL
echo    Opsi 2: PostgreSQL
echo.
echo    Setelah membuat database, copy credentials ke Railway Variables.
echo.

REM Step 6: Push to GitHub
echo 📝 Step 5: Push ke GitHub (trigger deployment)
echo.
echo    git add .
echo    git commit -m "Prepare for Railway deployment"
echo    git push origin main
echo.

REM Step 7: Final
echo ✅ Deployment dimulai!
echo.
echo    Monitor di: https://railway.app
echo.
echo    Setelah deployment berhasil, jalankan migrations:
echo    $ railway run php artisan migrate --force
echo.

pause
