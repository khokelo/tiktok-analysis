web: rm -rf bootstrap/cache/* ; php artisan cache:clear ; php artisan serve --host=0.0.0.0 --port=$PORT
release: rm -rf bootstrap/cache/* ; php artisan migrate --force --no-interaction ; php artisan db:seed --class=AdminUserSeeder 2>/dev/null || true
