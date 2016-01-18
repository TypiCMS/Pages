<?php

namespace TypiCMS\Modules\Pages\Providers;

use Config;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Pages;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Pages\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        $router->bind('uri', function ($uri) {

            $with = [
                'translations',
                'galleries',
                'galleries.translations',
                'galleries.files',
                'galleries.files.translations',
            ];

            if ($uri === '/') {
                return Pages::getFirstBy('is_home', 1, $with);
            }

            // Only locale in url
            if (
                in_array($uri, config('translatable.locales')) &&
                (
                    config('app.fallback_locale') != $uri ||
                    config('typicms.main_locale_in_url')
                )
            ) {
                return Pages::getFirstBy('is_home', 1, $with);
            }

            return Pages::getFirstByUri($uri, config('app.locale'), $with);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function (Router $router) {

            /*
             * Admin routes
             */
            $router->resource('admin/pages', 'AdminController');
            $router->post('admin/pages/sort', ['as' => 'admin.pages.sort', 'uses' => 'AdminController@sort']);

            /*
             * API routes
             */
            $router->resource('api/pages', 'ApiController');

            /*
             * Front office routes
             */
            if (config('typicms.lang_chooser')) {
                $router->get('/', 'PublicController@langChooser');
            } elseif (config('typicms.main_locale_in_url')) {
                $router->get('/', 'PublicController@redirectToHomepage');
            }
            foreach (config('translatable.locales') as $locale) {
                if (
                    config('app.fallback_locale') != $locale ||
                    config('typicms.main_locale_in_url')
                ) {
                    $router->get('{uri}', ['prefix' => $locale, 'uses' => 'PublicController@uri'])->where('uri', '(.*)');
                }
            }
            $router->get('{uri}', 'PublicController@uri')->where('uri', '(.*)');
        });
    }
}
