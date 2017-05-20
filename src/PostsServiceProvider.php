<?php

namespace Wateringcart;

use Illuminate\Auth\Events\Logout;
use Illuminate\Session\SessionManager;
use Illuminate\Support\ServiceProvider;

class PostsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        // The publication files to publish
        $configPath = __DIR__ . '/../config/config.php';
        $this->publishes([ $configPath=> config_path('posts.php')]);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
        if ( ! class_exists('CreatePostsTable')) {
            // Publish the migration
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_posts_table.php' => database_path('migrations/'.$timestamp.'_create_posts_table.php'),
            ], 'migrations');
        }
    }

}
