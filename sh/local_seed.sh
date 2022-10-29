php artisan db:wipe --force

composer dump-autoload

php artisan db:seed --class="Database\Seeders\Dev\SQLImportSeeder" --force

sh sh/package.sh

php artisan db:seed --class=DevSeeder --force

