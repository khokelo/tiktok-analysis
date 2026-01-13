#!/bin/bash

# Pre-deployment checks
# Run this script before deploying to ensure everything is ready

set -e

echo "🔍 Running pre-deployment checks..."

# Check PHP version
echo "✓ Checking PHP version..."
PHP_VERSION=$(php -v | head -n 1)
echo "  Found: $PHP_VERSION"

# Check composer
echo "✓ Checking Composer..."
composer --version

# Check Node.js
echo "✓ Checking Node.js..."
npm --version

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ .env file not found!"
    exit 1
fi

# Check required environment variables
echo "✓ Checking required environment variables..."
REQUIRED_VARS=("APP_NAME" "APP_KEY" "DB_HOST" "DB_DATABASE" "DB_USERNAME")

for var in "${REQUIRED_VARS[@]}"; do
    if grep -q "^$var=" .env; then
        echo "  ✓ $var is set"
    else
        echo "  ⚠️  Warning: $var might not be set"
    fi
done

# Check git status
echo "✓ Checking git status..."
if git status --porcelain | grep -q .; then
    echo "  ⚠️  Warning: You have uncommitted changes"
else
    echo "  ✓ Working directory is clean"
fi

# Test database connection (optional)
echo "✓ Testing database connection..."
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection successful';" || echo "⚠️  Warning: Could not connect to database"

echo ""
echo "✅ Pre-deployment checks completed!"
echo ""
echo "Next steps:"
echo "1. Commit all changes: git commit -m 'Deploy to Railway'"
echo "2. Push to main branch: git push origin main"
echo "3. GitHub Actions will automatically deploy"
