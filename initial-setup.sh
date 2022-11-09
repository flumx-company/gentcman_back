php artisan migrate:refresh --seed
php artisan passport:install
mkdir ./storage/app/public/uploads
mkdir ./storage/app/public/uploads/images
chmod -R 777 storage
cp /var/www/html/gentcman_back/public/{copy_icon.png,Facebook.png,Instagram.png,logo_black.png,logo_white.png,Telegram.png,Viber.png} /var/www/html/gentcman_back/storage/app/public/uploads/images