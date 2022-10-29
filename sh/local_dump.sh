php artisan db:wipe --force

sh update.sh

php artisan db:seed --class="Database\Seeders\Dev\SQLDumpSeeder" --force


