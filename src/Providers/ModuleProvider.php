<?php
namespace TypiCMS\Modules\Pages\Providers;

use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Lang;
use TypiCMS\Modules\Pages\Events\ResetChildren;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\Modules\Pages\Models\PageTranslation;
use TypiCMS\Modules\Pages\Observers\HomePageObserver;
use TypiCMS\Modules\Pages\Observers\SortObserver;
use TypiCMS\Modules\Pages\Observers\UriObserver;
use TypiCMS\Modules\Pages\Repositories\CacheDecorator;
use TypiCMS\Modules\Pages\Repositories\EloquentPage;
use TypiCMS\Modules\Pages\Services\Form\PageForm;
use TypiCMS\Modules\Pages\Services\Form\PageFormLaravelValidator;
use TypiCMS\Observers\FileObserver;
use TypiCMS\Services\Cache\LaravelCache;
use View;

class ModuleProvider extends ServiceProvider
{

    public function boot()
    {
        // Add dirs
        View::addNamespace('pages', __DIR__ . '/../views/');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'pages');
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php', 'typicms.pages'
        );
        $this->publishes([
            __DIR__ . '/../migrations/' => base_path('/database/migrations'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'Pages',
            'TypiCMS\Modules\Pages\Facades\Facade'
        );

        // Observers
        Page::observe(new FileObserver);
        Page::observe(new HomePageObserver);
        Page::observe(new SortObserver);
        PageTranslation::observe(new UriObserver);
    }

    public function register()
    {

        $app = $this->app;

        /**
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Pages\Providers\RouteServiceProvider');

        /**
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Pages\Composers\SideBarViewComposer');

        /**
         * Events
         */
        $app->events->subscribe(new ResetChildren);

        /**
         * Store all uris
         */
        $this->app->singleton('TypiCMS.pages.uris', function (Application $app) {
            return $app->make('TypiCMS\Modules\Pages\Repositories\PageInterface')->getAllUris();
        });

        $app->bind('TypiCMS\Modules\Pages\Repositories\PageInterface', function (Application $app) {
            $repository = new EloquentPage(new Page);
            if (! Config::get('app.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], ['pages', 'galleries'], 10);

            return new CacheDecorator($repository, $laravelCache);
        });

        $app->bind('TypiCMS\Modules\Pages\Services\Form\PageForm', function (Application $app) {
            return new PageForm(
                new PageFormLaravelValidator($app['validator']),
                $app->make('TypiCMS\Modules\Pages\Repositories\PageInterface')
            );
        });

    }
}
