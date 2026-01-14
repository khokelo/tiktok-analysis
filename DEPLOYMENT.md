# Deployment Guide

Panduan deploy aplikasi TikTok Analysis ke berbagai platform.

## Local Development

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- npm
- SQLite (atau MySQL untuk production)

### Quick Start

```bash
# 1. Install dependencies
composer install
npm install --legacy-peer-deps

# 2. Setup environment
cp .env.local .env

# 3. Build assets
npm run build

# 4. Start servers (di 2 terminal berbeda)
# Terminal 1
npm run dev

# Terminal 2
php -S 127.0.0.1:8000 -t public
```

Akses: http://localhost:8000

## Production Deployment

### Using Docker

```bash
# Build docker image
docker build -t tiktok-analysis .

# Run container
docker run -d -p 8000:8000 \
  -e APP_KEY="base64:..." \
  -e DB_CONNECTION=mysql \
  -e DB_HOST=mysql.example.com \
  -e DB_PORT=3306 \
  -e DB_DATABASE=tiktok_analysis \
  -e DB_USERNAME=root \
  -e DB_PASSWORD=password \
  tiktok-analysis
```

### Using Railway

1. Connect GitHub repository to Railway
2. Add environment variables:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_KEY=base64:...`
   - Database credentials

3. Deploy

### Using Traditional Server

```bash
# 1. Upload files to server
scp -r . user@server:/var/www/tiktok-analysis

# 2. SSH into server
ssh user@server

# 3. Install dependencies
cd /var/www/tiktok-analysis
composer install --no-dev
npm install --legacy-peer-deps
npm run build

# 4. Setup environment
cp .env.local .env
php artisan key:generate

# 5. Setup database
php artisan migrate --force

# 6. Configure web server (Nginx/Apache)
# Point document root to: /var/www/tiktok-analysis/public

# 7. Start queue (if needed)
php artisan queue:work --daemon

# 8. Setup supervisor for queue processing
# (See Supervisor configuration)
```

## Environment Variables

### Development (.env.local)

```env
APP_NAME="TikTok Analysis"
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:...

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

SESSION_DRIVER=file
CACHE_DRIVER=file
```

### Production

```env
APP_NAME="TikTok Analysis"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...

DB_CONNECTION=mysql
DB_HOST=mysql.example.com
DB_PORT=3306
DB_DATABASE=tiktok_analysis
DB_USERNAME=root
DB_PASSWORD=secure_password

SESSION_DRIVER=database
CACHE_DRIVER=redis
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=username
MAIL_PASSWORD=password
MAIL_ENCRYPTION=tls
```

## Database Setup

### SQLite (Development)

Database file sudah tersedia di `database/database.sqlite`.

### MySQL (Production)

```sql
-- Import schema
mysql -u root -p tiktok_analysis < database/database.sql

-- Run migrations
php artisan migrate --force
```

## SSL Certificate

### Let's Encrypt (Recommended)

```bash
# Using Certbot
sudo certbot certonly --standalone -d yourdomain.com

# Renew automatically
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

### Configure Nginx/Apache

Point SSL certificate paths to `/etc/letsencrypt/live/yourdomain.com/`

## Monitoring

### Check Application Status

```bash
# Check Laravel configuration
php artisan config:cache
php artisan route:cache

# Check logs
tail -f storage/logs/laravel.log

# Check queue processing
php artisan queue:failed
```

### Performance Optimization

```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

## Backup Strategy

```bash
# Backup database
mysqldump -u root -p tiktok_analysis > backup_$(date +%Y%m%d).sql

# Backup files
tar -czf tiktok-analysis-backup-$(date +%Y%m%d).tar.gz \
  storage/app/uploads \
  .env \
  database/

# Upload to cloud storage
aws s3 cp backup_*.sql s3://backups/
aws s3 cp tiktok-analysis-backup-*.tar.gz s3://backups/
```

## Troubleshooting

### Database Connection Error

```bash
# Check database credentials in .env
# Verify database server is running
# Check firewall rules
```

### Permission Issues

```bash
# Fix storage permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Fix ownership (if needed)
sudo chown -R www-data:www-data /var/www/tiktok-analysis
```

### High Memory Usage

```bash
# Clear cache
php artisan cache:clear

# Optimize database
php artisan tinker
# Run: DB::statement('OPTIMIZE TABLE sales; OPTIMIZE TABLE tiktok_sales;');
```

## Support

For issues, create an issue on GitHub: https://github.com/khokelo/tiktok-analysis/issues
