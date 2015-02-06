<?php
namespace TypiCMS\Modules\Pages\Providers;

use Config;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Pages\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        $router->model('pages', 'TypiCMS\Modules\Pages\Models\Page');
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function($router) {
            /**
             * Admin routes
             */
            $router->resource('admin/pages', 'AdminController');
            $router->post('admin/pages/sort', array('as' => 'admin.pages.sort', 'uses' => 'AdminController@sort'));

            /**
             * API routes
             */
            $router->resource('api/pages', 'ApiController');

            /**
             * Front office routes
             */
            $router->group(['before' => 'visitor.publicAccess'], function ($router) {
                $router->get('{uri}', 'PublicController@uri')->where('uri', '(.*)');
            });
        });
    }

}
