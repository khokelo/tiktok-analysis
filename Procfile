web: php artisan serve --host=0.0.0.0 --port=$PORT
release: php artisan migrate --force --no-interaction ; php artisan db:seed --class=AdminUserSeeder 2>/dev/null || true
