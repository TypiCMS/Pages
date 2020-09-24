<?php

namespace TypiCMS\Modules\Pages\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Pages\Composers\SidebarViewComposer;
use TypiCMS\Modules\Pages\Facades\Pages;
use TypiCMS\Modules\Pages\Facades\PageSections;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\Modules\Pages\Models\PageSection;
use TypiCMS\Modules\Pages\Observers\AddToMenuObserver;
use TypiCMS\Modules\Pages\Observers\HomePageObserver;
use TypiCMS\Modules\Pages\Observers\UriObserver;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'typicms.pages');
        $this->mergeConfigFrom(__DIR__.'/../config/config-sections.php', 'typicms.page_sections');
        $this->mergeConfigFrom(__DIR__.'/../config/permissions.php', 'typicms.permissions');

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['pages' => []], $modules));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'pages');

        $this->publishes([
            __DIR__.'/../database/migrations/create_pages_tables.php.stub' => getMigrationFileName('create_pages_tables'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/pages'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../database/seeders/PageSeeder.php' => database_path('seeders/PageSeeder.php'),
        ], 'seeders');

        AliasLoader::getInstance()->alias('Pages', Pages::class);
        AliasLoader::getInstance()->alias('PageSections', PageSections::class);

        // Observers
        Page::observe(new HomePageObserver());
        Page::observe(new AddToMenuObserver());
        Page::observe(new UriObserver());

        /*
         * Sidebar view composer
         */
        $this->app->view->composer('core::admin._sidebar', SidebarViewComposer::class);
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register(RouteServiceProvider::class);

        $app->bind('Pages', Page::class);
        $app->bind('PageSections', PageSection::class);
    }
}
