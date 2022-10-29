php artisan db:wipe --force

composer install
sh update.sh

php artisan db:seed --class=DevSeeder --force

