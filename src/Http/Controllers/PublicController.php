<?php
namespace TypiCMS\Modules\Pages\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use TypiCMS;
use TypiCMS\Modules\Core\Http\Controllers\BasePublicController;
use TypiCMS\Modules\Pages\Repositories\PageInterface;

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
    public function uri($page = null)
    {
        if (!$page) {
            abort('404');
        }

        if ($page->private && !Auth::check()) {
            abort('403');
        }

        if ($page->redirect) {
            $childUri = $page->children->first()->uri();
            return redirect($childUri);
        }

        // get submenu
        $children = $this->repository->getSubMenu($page->uri);

        $templateDir = 'pages::public.';
        $template = $page->template ? : 'default';

        if (!view()->exists($templateDir . $template)) {
            info('Template ' . $template . ' not found, switching to default template.');
            $template = 'default';
        }

        return response()->view($templateDir . $template, compact('children', 'page'));
    }

    /**
     * Get browser language or default locale and redirect to homepage
     *
     * @return Redirect
     */
    public function redirectToHomepage()
    {
        $homepage = $this->repository->getFirstBy('is_home', 1);
        $locales = TypiCMS::getPublicLocales();
        $locale = config('app.locale');
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $locale = substr(getenv('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            !in_array($locale, $locales) && $locale = config('app.locale');
        }
        return redirect($homepage->uri($locale));
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
