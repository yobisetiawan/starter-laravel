if [ "$1" == 'up' ]
then
php artisan up
else
php artisan down --secret="1234"
fi
