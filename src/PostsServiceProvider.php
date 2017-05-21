<?php

namespace Wateringcart;

use Illuminate\Auth\Events\Logout;
use Illuminate\Session\SessionManager;
use Illuminate\Support\ServiceProvider;

// use Wateringcart\MakePostCommand;

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
        $this->publishes([
            __DIR__ . '/config/posts.php' => config_path('posts.php'),
        ]);
 
        // Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakePostCommand::class,
            ]);
        }


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
                __DIR__.'/database/migrations/create_posts_table.php' => database_path('migrations/'.$timestamp.'_create_posts_table.php'),
            ], 'migrations');
        }
    }

}
