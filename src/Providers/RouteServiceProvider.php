<?php

namespace TypiCMS\Modules\Pages\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Pages\Http\Controllers\AdminController;
use TypiCMS\Modules\Pages\Http\Controllers\ApiController;
use TypiCMS\Modules\Pages\Http\Controllers\PublicController;
use TypiCMS\Modules\Pages\Http\Controllers\SectionsAdminController;
use TypiCMS\Modules\Pages\Http\Controllers\SectionsApiController;

class RouteServiceProvider extends ServiceProvider
{
    public function map(): void
    {
        /*
         * Admin routes
         */
        Route::middleware('admin')->prefix('admin')->name('admin::')->group(function (Router $router) {
            $router->get('pages', [AdminController::class, 'index'])->name('index-pages')->middleware('can:read pages');
            $router->get('pages/create', [AdminController::class, 'create'])->name('create-page')->middleware('can:create pages');
            $router->get('pages/{page}/edit', [AdminController::class, 'edit'])->name('edit-page')->middleware('can:read pages');
            $router->post('pages', [AdminController::class, 'store'])->name('store-page')->middleware('can:create pages');
            $router->put('pages/{page}', [AdminController::class, 'update'])->name('update-page')->middleware('can:update pages');

            $router->get('pages/{page}/sections/create', [SectionsAdminController::class, 'create'])->name('create-page_section')->middleware('can:create page_sections');
            $router->get('pages/{page}/sections/{section}/edit', [SectionsAdminController::class, 'edit'])->name('edit-page_section')->middleware('can:read page_sections');
            $router->post('pages/{page}/sections', [SectionsAdminController::class, 'store'])->name('store-page_section')->middleware('can:create page_sections');
            $router->put('pages/{page}/sections/{section}', [SectionsAdminController::class, 'update'])->name('update-page_section')->middleware('can:update page_sections');
            $router->post('pages/{page}/sections/sort', [SectionsAdminController::class, 'sort'])->name('sort-page_sections');

            $router->get('sections', [SectionsAdminController::class, 'index'])->name('index-page_sections')->middleware('can:read page_sections');
            $router->delete('sections/{section}', [SectionsAdminController::class, 'destroyMultiple'])->name('destroy-page_section')->middleware('can:delete page_sections');
        });

        /*
         * API routes
         */
        Route::middleware(['api', 'auth:api'])->prefix('api')->group(function (Router $router) {
            $router->get('pages', [ApiController::class, 'index'])->middleware('can:read pages');
            $router->get('pages/links-for-editor', [ApiController::class, 'linksForEditor'])->middleware('can:read pages');
            $router->patch('pages/{page}', [ApiController::class, 'updatePartial'])->middleware('can:update pages');
            $router->post('pages/sort', [ApiController::class, 'sort'])->middleware('can:update pages');
            $router->delete('pages/{page}', [ApiController::class, 'destroy'])->middleware('can:delete pages');
            $router->get('pages/{page}/sections', [SectionsApiController::class, 'index'])->middleware('can:read page_sections');
            $router->patch('pages/{page}/sections/{section}', [SectionsApiController::class, 'updatePartial'])->middleware('can:update page_sections');
            $router->delete('pages/{page}/sections/{section}', [SectionsApiController::class, 'destroy'])->middleware('can:delete page_sections');
        });

        /*
         * Front office routes
         */
        Route::middleware('public')->group(function (Router $router) {
            if (config('typicms.main_locale_in_url')) {
                if (config('typicms.lang_chooser')) {
                    $router->get('/', [PublicController::class, 'langChooser']);
                } else {
                    $router->get('/', [PublicController::class, 'redirectToHomepage']);
                }
            }
            foreach (locales() as $locale) {
                if (TypiCMS::mainLocale() !== $locale || config('typicms.main_locale_in_url')) {
                    $router->prefix($locale)->get('{uri}', [PublicController::class, 'uri'])->where('uri', '(.*)');
                }
            }
            $router->get('{uri}', [PublicController::class, 'uri'])->where('uri', '(.*)');
        });
    }
}
