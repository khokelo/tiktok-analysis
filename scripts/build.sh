#!/bin/bash

# Build and Deployment Script for Railway
# This script prepares the application for production deployment

set -e

echo "🔨 Starting build process..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ Error: .env file not found. Please copy .env.example to .env and configure it."
    exit 1
fi

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Install Node dependencies
echo "📦 Installing Node dependencies..."
npm install --legacy-peer-deps

# Build frontend assets
echo "🎨 Building frontend assets..."
npm run build

# Clear all caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate app key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:null" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Run seeders (optional - uncomment if needed)
# echo "🌱 Seeding database..."
# php artisan db:seed --force

# Cache configuration
echo "⚙️  Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions (if needed)
if [ -d "storage" ]; then
    echo "📁 Setting storage permissions..."
    chmod -R 775 storage bootstrap/cache
fi

echo "✅ Build process completed successfully!"
