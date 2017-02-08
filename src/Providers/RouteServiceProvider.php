<?php

namespace TypiCMS\Modules\Pages\Providers;

use Config;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\Pages\Facades\Pages;

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
     * @return null
     */
    public function boot()
    {
        parent::boot();

        Route::bind('uri', function ($uri) {
            $with = [
                'galleries',
                'galleries.files',
            ];

            if ($uri === '/') {
                return Pages::findBy('is_home', 1, $with);
            }

            // Only locale in url
            if (
                in_array($uri, locales()) &&
                (
                    config('app.fallback_locale') != $uri ||
                    config('typicms.main_locale_in_url')
                )
            ) {
                return Pages::with($with)->findBy('is_home', 1);
            }

            return Pages::getFirstByUri($uri, config('app.locale'), $with);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return null
     */
    public function map()
    {
        Route::group(['namespace' => $this->namespace], function (Router $router) {

            /*
             * Admin routes
             */
            $router->group(['middleware' => 'admin', 'prefix' => 'admin'], function (Router $router) {
                $router->get('pages', 'AdminController@index')->name('admin::index-pages');
                $router->get('pages/create', 'AdminController@create')->name('admin::create-page');
                $router->get('pages/{page}/edit', 'AdminController@edit')->name('admin::edit-page');
                $router->post('pages', 'AdminController@store')->name('admin::store-page');
                $router->put('pages/{page}', 'AdminController@update')->name('admin::update-page');
                $router->post('pages/sort', 'AdminController@sort')->name('admin::sort-pages');
                $router->patch('pages/{page}', 'AdminController@ajaxUpdate');
                $router->delete('pages/{page}', 'AdminController@destroy')->name('api::destroy-page');
            });

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
            foreach (locales() as $locale) {
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
