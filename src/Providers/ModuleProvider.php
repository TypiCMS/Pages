<?php

namespace TypiCMS\Modules\Pages\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Pages\Composers\SidebarViewComposer;
use TypiCMS\Modules\Pages\Events\ResetChildren;
use TypiCMS\Modules\Pages\Facades\Pages;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\Modules\Pages\Observers\AddToMenuObserver;
use TypiCMS\Modules\Pages\Observers\HomePageObserver;
use TypiCMS\Modules\Pages\Observers\SortObserver;
use TypiCMS\Modules\Pages\Observers\UriObserver;
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
        $this->publishes([
            __DIR__.'/../../public' => public_path(),
        ], 'assets');

        AliasLoader::getInstance()->alias('Pages', Pages::class);

        // Observers
        Page::observe(new HomePageObserver());
        Page::observe(new SortObserver());
        Page::observe(new AddToMenuObserver());
        Page::observe(new UriObserver());
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register(RouteServiceProvider::class);

        /*
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', SidebarViewComposer::class);

        /*
         * Events
         */
        $app->events->subscribe(new ResetChildren());

        $app->bind('Pages', EloquentPage::class);
    }
}
