<?php
namespace TypiCMS\Modules\Pages\Http\Controllers;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Notification;
use Redirect;
use TypiCMS;
use TypiCMS\Http\Controllers\BasePublicController;
use TypiCMS\Modules\Pages\Repositories\PageInterface;
use View;

class PublicController extends BasePublicController
{

    public function __construct(PageInterface $page)
    {
        parent::__construct($page);
    }

    /**
     * Page uri : lang/slug
     *
     * @return void
     */
    public function uri($uri = null)
    {
        if ($uri == '/') {
            if (config('typicms.lang_chooser')) {
                return $this->langChooser();
            }
            if (config('typicms.main_locale_in_url')) {
                return $this->redirectToBrowserLanguage();
            }
            $page = $this->repository->getFirstBy('is_home', 1);
        } else if (
            in_array($uri, config('translatable.locales')) &&
            (config('app.fallback_locale') != config('app.locale') ||
            config('typicms.main_locale_in_url'))
        ) {
            $page = $this->repository->getFirstBy('is_home', 1);
        } else {
            $page = $this->repository->getFirstByUri($uri, config('app.locale'));
        }

        if (! $page) {
            abort('404');
        }

        if ($page->private && ! Sentry::check()) {
            abort('403');
        }

        if ($page->redirect) {
            $childUri = $page->children->first()->uri;
            return Redirect::to($childUri);
        }

        TypiCMS::setModel($page);

        // get submenu
        $children = $this->repository->getSubMenu($page->uri);

        $defaultTemplate = 'default';

        $template = $page->template ? $page->template : $defaultTemplate ;
        try {
            $view = view('pages::public.' . $template);
        } catch (InvalidArgumentException $e) {
            Notification::error('<b>Error:</b> Template “' . $template . '” not found.');
            $view = view('pages::public.' . $defaultTemplate);
        }

        return $view->with(compact('children', 'page'));
    }

    /**
     * Redirect to browser language or default locale
     *
     * @return Redirect
     */
    public function redirectToBrowserLanguage()
    {
        $locales = TypiCMS::getPublicLocales();
        $locale = config('app.locale');
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $locale = substr(getenv('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            ! in_array($locale, $locales) && $locale = config('app.locale');
        }
        return Redirect::to($locale);
    }

    /**
     * Display the lang chooser
     *
     * @return void
     */
    public function langChooser()
    {
        $homepage = $this->repository->getFirstBy('is_home', 1);
        $locales = TypiCMS::getPublicLocales();
        return view('core::public.lang-chooser')
            ->with(compact('homepage', 'locales'));
    }
}
