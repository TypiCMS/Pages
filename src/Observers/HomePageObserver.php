<?php

namespace TypiCMS\Modules\Pages\Observers;

use TypiCMS\Modules\Pages\Models\Page;

class HomePageObserver
{
    /**
     * If a new homepage is defined, cancel previous homepage.
     */
    public function saving(Page $model)
    {
        if ($model->is_home) {
            $query = Page::where('is_home', 1);
            if ($model->id) {
                $query->where('id', '!=', $model->id);
            }
            $query->update(['is_home' => 0]);
        }
    }

    /**
     * If there is no homepage, set the first page as homepage.
     */
    public function saved(Page $model)
    {
        if (Page::where('is_home', 1)->count() === 0) {
            Page::whereNull('parent_id')->orderBy('position')->take(1)->update(['is_home' => 1]);
        }
    }
}
