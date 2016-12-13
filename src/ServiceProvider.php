<?php

namespace Microit\LaravelAdminBaseStandalone;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Routing\Router;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        /**
         * Routes
         */
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/../routes.php';
        }

        /**
         * Views
         */
        $this->loadViewsFrom(__DIR__.'/../views', 'laravel-admin-base-standalone');

        /**
         * Migrations
         */
        $this->loadMigrationsFrom( __DIR__.'/../migrations/');

        /**
         * Config
         */
        $this->publishes([ __DIR__ . '/../config/laravel-admin-base-standalone.php' => config_path('laravel-admin-base-standalone.php')],'config');

        /**
         * Lang
         */
        $this->loadTranslationsFrom( __DIR__ .'/../lang', 'laravel-admin-base-standalone');
    }

    /**
     * @return void
     */
    public function register()
    {
        // Load RBAC
        $this->app->register(\DCN\RBAC\RBACServiceProvider::class);

        /**
         * Commands
         */
        $this->commands(UserCreate::class);
        $this->commands(UserEdit::class);
        $this->commands(DeleteUnusedMedia::class);
    }
}
