<?php

namespace Msc\SliderConstructor;

use Illuminate\Support\ServiceProvider;

class SliderConstructorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/slider-constructor.php',
            'slider-constructor'
        );
    }

    public function boot(): void
    {
        // Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Views — namespace: slider-constructor::
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'slider-constructor');

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publishable assets
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/slider'),
        ], 'slider-constructor-assets');

        $this->publishes([
            __DIR__ . '/../config/slider-constructor.php' => config_path('slider-constructor.php'),
        ], 'slider-constructor-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/slider-constructor'),
        ], 'slider-constructor-views');
    }
}
