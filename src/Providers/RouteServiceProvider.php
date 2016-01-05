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

            if ($uri === '/') {
                return Pages::getFirstBy('is_home', 1);
            }

            // Only locale in url
            if (
                in_array($uri, config('translatable.locales')) &&
                (
                    config('app.fallback_locale') != $uri ||
                    config('typicms.main_locale_in_url')
                )
            ) {
                return Pages::getFirstBy('is_home', 1);
            }

            return Pages::getFirstByUri($uri, config('app.locale'), ['translations', 'galleries']);
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
            $router->get('admin/pages', ['as' => 'admin.pages.index', 'uses' => 'AdminController@index']);
            $router->get('admin/pages/create', ['as' => 'admin.pages.create', 'uses' => 'AdminController@create']);
            $router->get('admin/pages/{page}/edit', ['as' => 'admin.pages.edit', 'uses' => 'AdminController@edit']);
            $router->post('admin/pages', ['as' => 'admin.pages.store', 'uses' => 'AdminController@store']);
            $router->put('admin/pages/{page}', ['as' => 'admin.pages.update', 'uses' => 'AdminController@update']);
            $router->post('admin/pages/sort', ['as' => 'admin.pages.sort', 'uses' => 'AdminController@sort']);

            /*
             * API routes
             */
            $router->get('api/pages', ['as' => 'api.pages.index', 'uses' => 'ApiController@index']);
            $router->put('api/pages/{page}', ['as' => 'api.pages.update', 'uses' => 'ApiController@update']);
            $router->delete('api/pages/{page}', ['as' => 'api.pages.destroy', 'uses' => 'ApiController@destroy']);

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
