#!/bin/bash

# Railway Deployment Bootstrap Script
# This script prepares the application for Railway deployment

set -e

echo "🚀 Starting Laravel Application on Railway..."

# Step 1: Run migrations
echo "📦 Running database migrations..."
php artisan migrate --force || {
    echo "⚠️  Migration failed, but continuing..."
}

# Step 2: Seed admin user if needed
echo "👤 Seeding admin user..."
php artisan db:seed --class=AdminUserSeeder || {
    echo "⚠️  Seeding failed, admin user may already exist..."
}

# Step 3: Clear caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# Step 4: Start server
echo "✅ Starting server..."
php artisan serve --host=0.0.0.0 --port=$PORT
