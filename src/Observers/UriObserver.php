<?php
namespace TypiCMS\Modules\Pages\Observers;

use Config;
use TypiCMS\Modules\Pages\Models\PageTranslation;

class UriObserver
{

    /**
     * On create, update uri
     * 
     * @param  PageTranslation $model
     * @return void
     */
    public function creating(PageTranslation $model)
    {

        $model->uri = null;

        if ($model->slug) {
            $uri = $model->locale . '/' . $model->slug;
        } else {
            $uri = $model->locale;
        }
        if (
            Config::get('app.fallback_locale') == $model->locale &&
            ! config('typicms.main_locale_in_url')
        ) {
            $uri = $model->slug;
        }

        $model->uri = $this->incrementWhileExists($model, $uri);

    }

    /**
     * On update, change uri
     * 
     * @param  PageTranslation $model
     * @return void
     */
    public function updating(PageTranslation $model)
    {

        $parentUri = $this->getParentUri($model);

        if ($parentUri) {
            $uri = $parentUri;
            if ($model->slug) {
                $uri .= '/' . $model->slug;
            }
        } else {
            $uri = $model->slug;
        }

        $model->uri = $this->incrementWhileExists($model, $uri, $model->id);
    }

    /**
     * Get parent page’s URI
     *
     * @param  PageTranslation $model
     * @return string
     */
    private function getParentUri(PageTranslation $model)
    {
        if ($parentPage = $model->page->parent) {
            return $parentPage->translate($model->locale)->uri;
        }
        if (
            Config::get('app.fallback_locale') == $model->locale &&
            ! config('typicms.main_locale_in_url')
        ) {
            return '';
        }
        return $model->locale;
    }

    /**
     * Check if uri exists in all uris array
     *
     * @param  string  $uri
     * @param  integer $id
     * @return bool
     */
    private function uriExists($model, $uri, $id)
    {
        $found = $model->where('uri', $uri)->first();
        if ($found) {
            return true;
        }
        return false;
    }

    /**
     * Add '-x' on uri if it exists in page_translations table
     *  
     * @param  string  $uri
     * @param  integer $id in case of update, except this id
     * @return string
     */
    private function incrementWhileExists($model, $uri, $id = 0)
    {
        $originalUri = $uri;

        $i = 0;
        // Check if uri is unique
        while ($this->uriExists($model, $uri, $id)) {
            $i++;
            // increment uri if it exists
            $uri = $originalUri . '-' . $i;
        }

        return $uri;
    }
}
