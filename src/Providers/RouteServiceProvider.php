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

            if ($uri === '/') {
                return Pages::findBy('is_home', 1);
            }

            // Only locale in url
            if (
                in_array($uri, locales()) &&
                (
                    config('app.fallback_locale') != $uri ||
                    config('typicms.main_locale_in_url')
                )
            ) {
                return Pages::findBy('is_home', 1);
            }

            return Pages::getFirstByUri($uri, config('app.locale'));
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
                $router->get('pages', 'AdminController@index')->name('admin::index-pages')->middleware('can:see-all-pages');
                $router->get('pages/create', 'AdminController@create')->name('admin::create-page')->middleware('can:create-page');
                $router->get('pages/{page}/edit', 'AdminController@edit')->name('admin::edit-page')->middleware('can:update-page');
                $router->get('pages/{page}/files', 'AdminController@files')->name('admin::edit-page-files')->middleware('can:update-page');
                $router->post('pages', 'AdminController@store')->name('admin::store-page')->middleware('can:create-page');
                $router->put('pages/{page}', 'AdminController@update')->name('admin::update-page')->middleware('can:update-page');
                $router->post('pages/sort', 'AdminController@sort')->name('admin::sort-pages')->middleware('can:update-page');
                $router->patch('pages/{ids}', 'AdminController@ajaxUpdate')->name('admin::update-page-ajax')->middleware('can:update-page');
                $router->delete('pages/{page}', 'AdminController@destroy')->name('admin::destroy-page')->middleware('can:delete-page');
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
