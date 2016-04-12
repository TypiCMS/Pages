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
            $router->get('admin/pages', 'AdminController@index')->name('admin::index-pages');
            $router->get('admin/pages/create', 'AdminController@create')->name('admin::create-page');
            $router->get('admin/pages/{page}/edit', 'AdminController@edit')->name('admin::edit-page');
            $router->post('admin/pages', 'AdminController@store')->name('admin::store-page');
            $router->put('admin/pages/{page}', 'AdminController@update')->name('admin::update-page');
            $router->post('admin/pages/sort', 'AdminController@sort')->name('admin::sort-pages');

            /*
             * API routes
             */
            $router->get('api/pages', 'ApiController@index')->name('api::index-pages');
            $router->put('api/pages/{page}', 'ApiController@update')->name('api::update-page');
            $router->delete('api/pages/{page}', 'ApiController@destroy')->name('api::destroy-page');

            /*
             * Front office routes
             */
            if (config('typicms.main_locale_in_url')) {
                if (config('typicms.lang_chooser')) {
                    $router->get('/', 'PublicController@langChooser');
                } else {
                    $router->get('/', 'PublicController@redirectToHomepage');
                }
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
