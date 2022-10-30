composer dump-autoload

php artisan db:seed --class=ManualSeeder --force
php artisan storage:link
