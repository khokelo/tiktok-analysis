#!/bin/bash
# 🚀 Script Setup Railway Deployment
# Automatisasi setup environment variables dan deployment

set -e

echo "╔════════════════════════════════════════════════════════╗"
echo "║       Railway Deployment Setup Helper                  ║"
echo "║       TikTok Analysis Application                       ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""

# Check if Railway CLI is installed
if ! command -v railway &> /dev/null; then
    echo "❌ Railway CLI tidak ditemukan!"
    echo "📝 Install Railway CLI:"
    echo "   npm install -g @railway/cli"
    echo ""
    exit 1
fi

echo "✅ Railway CLI ditemukan"
echo ""

# Step 1: Login
echo "📝 Step 1: Login ke Railway..."
railway login
echo "✅ Login berhasil"
echo ""

# Step 2: Link project
echo "📝 Step 2: Link ke Railway project..."
echo "   Pilih project TikTok Analysis saat diminta"
railway link
echo "✅ Project linked"
echo ""

# Step 3: Generate APP_KEY
echo "📝 Step 3: Generate APP_KEY..."
if [ ! -f .env.production ]; then
    echo "⚠️  .env.production tidak ditemukan. Menggunakan .env.local"
    APP_KEY=$(php artisan key:generate --show 2>/dev/null || echo "base64:GENERATE_NEW_KEY")
else
    APP_KEY=$(grep "APP_KEY=" .env.production | cut -d '=' -f 2)
    if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
        APP_KEY=$(php artisan key:generate --show 2>/dev/null || echo "base64:GENERATE_NEW_KEY")
    fi
fi

echo "   APP_KEY: $APP_KEY"
echo ""

# Step 4: Check & add environment variables
echo "📝 Step 4: Setup Environment Variables..."
echo ""
echo "   Akan di-set ke Railway:"
echo "   ✓ APP_ENV=production"
echo "   ✓ APP_DEBUG=false"
echo "   ✓ LOG_LEVEL=warning"
echo "   ✓ SESSION_DRIVER=database"
echo "   ✓ CACHE_STORE=database"
echo "   ✓ QUEUE_CONNECTION=database"
echo ""

echo "⚠️  PENTING: Pastikan database sudah dibuat di Railway!"
echo "   Opsi 1: MySQL (recommended)"
echo "   Opsi 2: PostgreSQL"
echo ""
echo "   Setelah membuat database, copy environment variables:"
echo "   - DB_HOST atau DATABASE_URL"
echo "   - DB_USERNAME atau USER"
echo "   - DB_PASSWORD"
echo "   - DB_DATABASE atau DB"
echo ""
read -p "Lanjutkan? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Dibatalkan."
    exit 1
fi

echo ""
echo "📝 Step 5: Push ke GitHub (trigger deployment)..."
echo "   Pastikan sudah commit perubahan:"
echo "   $ git add ."
echo "   $ git commit -m 'Prepare for Railway deployment'"
echo "   $ git push origin main"
echo ""
read -p "Sudah push ke GitHub? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Silakan push ke GitHub terlebih dahulu:"
    echo "  $ git add ."
    echo "  $ git commit -m 'Prepare for Railway deployment'"
    echo "  $ git push origin main"
    exit 1
fi

echo ""
echo "📝 Step 6: Monitor deployment..."
echo "   Buka: https://railway.app"
echo "   Atau gunakan CLI:"
railway logs --tail

echo ""
echo "📝 Step 7: Run database migrations..."
echo "   Setelah deployment berhasil:"
echo "   $ railway run php artisan migrate --force"
echo "   $ railway run php artisan db:seed --class=AdminUserSeeder"
echo ""

echo "╔════════════════════════════════════════════════════════╗"
echo "║ ✅ Setup lengkap! Aplikasi sedang di-deploy ke Railway ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""
echo "📚 Dokumentasi lengkap: RAILWAY_DEPLOYMENT_GUIDE.md"
echo "🆘 Ada masalah? Lihat TROUBLESHOOTING.md"
echo ""
