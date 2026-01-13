web: php artisan migrate --force --no-interaction 2>/dev/null || sleep 5 && php artisan migrate --force --no-interaction ; php artisan cache:clear ; php artisan config:cache ; php artisan serve --host=0.0.0.0 --port=$PORT
release: for i in {1..3}; do php artisan migrate --force --no-interaction && break || sleep 2; done ; php artisan db:seed --class=AdminUserSeeder
