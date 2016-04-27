<?php

namespace TypiCMS\Modules\Pages\Events;

use Illuminate\Events\Dispatcher;
use TypiCMS\Modules\Pages\Models\Page;

class ResetChildren
{
    /**
     * Recursive method for emptying children’s uri
     * UriObserver will rebuild uris.
     *
     * @param Page $page
     *
     * @return void
     */
    public function resetChildrenUri(Page $page)
    {
        foreach ($page->children as $childPage) {
            $uris = $childPage->getTranslations('uri');
            foreach ($uris as $locale => $uri) {
                $childPage->forgetTranslation('uri', $locale);
            }
            $childPage->save();
            $this->resetChildrenUri($childPage);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     *
     * @return array
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen('page.resetChildrenUri', 'TypiCMS\Modules\Pages\Events\ResetChildren@resetChildrenUri');
    }
}
