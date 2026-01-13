web: rm -f bootstrap/cache/routes*.php bootstrap/cache/config.php ; php artisan cache:clear ; php artisan config:cache ; php artisan serve --host=0.0.0.0 --port=$PORT
release: rm -f bootstrap/cache/routes*.php ; php artisan migrate --force --no-interaction ; php artisan db:seed --class=AdminUserSeeder 2>/dev/null || true
