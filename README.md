README.md

1. Add posts to `composer.json`:

```
"wateringcart/laravel-posts": "dev-master",

```

2. Run `composer update` to pull down the code:

```
$ composer up

```

3. Edit `app/config/app.php` and add the provider, Add the alias:

```
'providers' => [
    // ... orther providers ...
    Wateringcart\PostsServiceProvider::class,
],

'aliases' => [
    // ... orther ServiceProvider aliases ...
    'Posts' => Wateringcart\PostsServiceProvider::class,
],
```

4. `vendor:publish` :

$ php artisan vendor:publish --provider="Wateringcart\PostsServiceProvider"

5. 