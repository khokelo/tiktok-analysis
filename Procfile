web: php artisan migrate --force --no-interaction ; php artisan cache:clear ; php artisan config:cache ; php artisan serve --host=0.0.0.0 --port=$PORT
release: php artisan migrate --force --no-interaction ; php artisan db:seed --class=AdminUserSeeder
