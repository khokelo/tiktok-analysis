web: php artisan migrate --force && php artisan cache:clear && php artisan config:cache && npm run build && php artisan serve --host=0.0.0.0 --port=$PORT
release: php artisan migrate --force && php artisan db:seed --class=AdminUserSeeder
