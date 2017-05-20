README.md

1. Add posts to `composer.json`:


2. Run `composer update` to pull down the code:


3. Edit `app/config/app.php` and add the provider:


4. Add the alias:


$ composer install


$ php artisan vendor:publish --provider="Wateringcart\PostsServiceProvider"