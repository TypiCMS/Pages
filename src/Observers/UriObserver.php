<?php

namespace TypiCMS\Modules\Pages\Observers;

use TypiCMS\Modules\Pages\Models\PageTranslation;

class UriObserver
{
    /**
     * On create, update uri.
     *
     * @param PageTranslation $model
     *
     * @return null
     */
    public function creating(PageTranslation $model)
    {
        $model->uri = $this->incrementWhileExists($model, $model->slug);
    }

    /**
     * On update, change uri.
     *
     * @param PageTranslation $model
     *
     * @return null
     */
    public function updating(PageTranslation $model)
    {
        $parentUri = $this->getParentUri($model);

        if ($parentUri) {
            $uri = $parentUri;
            if ($model->slug) {
                $uri .= '/'.$model->slug;
            }
        } else {
            $uri = $model->slug;
        }

        $model->uri = $this->incrementWhileExists($model, $uri, $model->id);
    }

    /**
     * Get parent page’s URI.
     *
     * @param PageTranslation $model
     *
     * @return string|null
     */
    private function getParentUri(PageTranslation $model)
    {
        if ($parentPage = $model->page->parent) {
            return $parentPage->translate($model->locale)->uri;
        }
    }

    /**
     * Check if uri exists.
     *
     * @param PageTranslation $model
     * @param string          $uri
     * @param int             $id
     *
     * @return bool
     */
    private function uriExists(PageTranslation $model, $uri, $id)
    {
        $query = $model->where('uri', $uri)
            ->where('locale', $model->locale);
        if ($id) {
            $query->where('id', '!=', $id);
        }

        if ($query->first()) {
            return true;
        }

        return false;
    }

    /**
     * Add '-x' on uri if it exists in page_translations table.
     *
     * @param PageTranslation $model
     * @param string          $uri
     * @param int             $id
     *
     * @return string|null
     */
    private function incrementWhileExists(PageTranslation $model, $uri, $id = null)
    {
        if (!$uri) {
            return;
        }

        $originalUri = $uri;

        $i = 0;
        // Check if uri is unique
        while ($this->uriExists($model, $uri, $id)) {
            $i++;
            // increment uri if it exists
            $uri = $originalUri.'-'.$i;
        }

        return $uri;
    }
}
