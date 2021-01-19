<?php

namespace LaravelArab\Tarjama;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use LaravelArab\Tarjama\Facades\Tarjama as TarjamaFacade;

class TarjamaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../publishable/config/tarjama.php' => config_path('tarjama.php'),
        ]);

        if (!class_exists('CreateTranslationsTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__ . '/../publishable/database/migrations/create_translations_table.php.stub' => database_path("/migrations/{$timestamp}_create_translations_table.php"),
            ], 'migrations');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../publishable/config/tarjama.php',
            'tarjama'
        );

        $loader = AliasLoader::getInstance();
        $loader->alias('Tarjama', TarjamaFacade::class);

        $this->app->singleton('tarjama', function () {
            return new Tarjama();
        });
    }
}
