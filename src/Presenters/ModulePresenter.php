<?php
namespace TypiCMS\Modules\Pages\Presenters;

use Config;
use TypiCMS\Presenters\Presenter;

class ModulePresenter extends Presenter
{

    /**
     * Get Uri without last segment
     *
     * @param  string $lang
     * @return string URI without last segment
     */
    public function parentUri($lang)
    {
        $translation = $this->entity->translate($lang);
        if (! $translation) {
            return $this->rootUri($lang);
        }
        $parentUri = $this->entity->translate($lang)->uri;
        if (! $parentUri) {
            return $this->rootUri($lang);
        }
        $parentUri = explode('/', $parentUri);
        array_pop($parentUri);
        $parentUri = implode('/', $parentUri) . '/';

        return $parentUri;
    }

    public function rootUri($lang)
    {
        if (
            ! config('typicms.lang_chooser') &&
            config('app.fallback_locale') == $lang &&
            ! config('typicms.main_locale_in_url')
        ) {
            return '/';
        }
        return $lang . '/';
    }
}
