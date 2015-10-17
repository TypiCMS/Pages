<?php

namespace TypiCMS\Modules\Pages\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Core\Observers\FileObserver;
use TypiCMS\Modules\Core\Services\Cache\LaravelCache;
use TypiCMS\Modules\Pages\Events\ResetChildren;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\Modules\Pages\Models\PageTranslation;
use TypiCMS\Modules\Pages\Observers\AddToMenuObserver;
use TypiCMS\Modules\Pages\Observers\HomePageObserver;
use TypiCMS\Modules\Pages\Observers\SortObserver;
use TypiCMS\Modules\Pages\Observers\UriObserver;
use TypiCMS\Modules\Pages\Repositories\CacheDecorator;
use TypiCMS\Modules\Pages\Repositories\EloquentPage;

class ModuleProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'typicms.pages'
        );

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['pages' => []], $modules));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'pages');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'pages');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/pages'),
        ], 'views');
        $this->publishes([
            __DIR__.'/../database' => base_path('database'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'Pages',
            'TypiCMS\Modules\Pages\Facades\Facade'
        );

        // Observers
        Page::observe(new FileObserver());
        Page::observe(new HomePageObserver());
        Page::observe(new SortObserver());
        Page::observe(new AddToMenuObserver());
        PageTranslation::observe(new UriObserver());
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Pages\Providers\RouteServiceProvider');

        /*
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Pages\Composers\SidebarViewComposer');

        /*
         * Events
         */
        $app->events->subscribe(new ResetChildren());

        $app->bind('TypiCMS\Modules\Pages\Repositories\PageInterface', function (Application $app) {
            $repository = new EloquentPage(new Page());
            if (!config('typicms.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], ['pages', 'galleries'], 10);

            return new CacheDecorator($repository, $laravelCache);
        });
    }
}
