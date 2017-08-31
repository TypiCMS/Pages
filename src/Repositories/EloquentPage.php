<?php

namespace TypiCMS\Modules\Pages\Repositories;

use TypiCMS\Modules\Core\Repositories\EloquentRepository;
use TypiCMS\Modules\Pages\Facades\Pages;
use TypiCMS\Modules\Pages\Models\Page;

class EloquentPage extends EloquentRepository
{
    protected $repositoryId = 'pages';

    protected $model = Page::class;

    /**
     * Get a page by its uri.
     *
     * @param string $uri
     * @param string $locale
     * @param array  $with
     *
     * @return TypiCMS\Modules\Models\Page $model
     */
    public function getFirstByUri($uri, $locale, array $with = [])
    {
        $repository = $this->with($with);
        if (!request('preview')) {
            $repository->where(column('status'), '1');
        }

        return $repository->findBy(column('uri'), $uri);
    }

    /**
     * Get submenu for a page.
     *
     * @return Collection
     */
    public function getSubMenu($uri, $all = false)
    {
        $rootUriArray = explode('/', $uri);
        $uri = $rootUriArray[0];
        $locale = config('app.locale');
        if (in_array($uri, locales())) {
            if (isset($rootUriArray[1])) {
                $uri .= '/'.$rootUriArray[1]; // add next part of uri in locale
            }
        }

        $repository = Pages::where(column('uri'), '!=', $uri);

        if (!$all) {
            $repository->where(column('status'), '1');
        }

        $models = $repository->orderBy('position', 'asc')
            ->findWhere([column('uri'), 'LIKE', '\"'.$uri.'%'])
            ->noCleaning()
            ->nest();

        return $models;
    }

    /**
     * Get pages linked to a module.
     *
     * @return array
     */
    public function getForRoutes()
    {
        $pages = $this->where('module', '!=', '')
            ->findAll()
            ->all();

        return $pages;
    }

    /**
     * Get sort data.
     *
     * @param int   $position
     * @param array $item
     *
     * @return array
     */
    protected function getSortData($position, $item)
    {
        return [
            'position' => $position,
            'parent_id' => $item['parent_id'],
            'private'   => $item['private'],
        ];
    }

    /**
     * Get all translated pages for a select/options.
     *
     * @return array
     */
    public function allForSelect()
    {
        $pages = $this->findAll()
            ->nest()
            ->listsFlattened();

        return ['' => ''] + $pages;
    }

    /**
     * Fire event to reset children’s uri
     * Only applicable on nestable collections.
     *
     * @param Page $page
     *
     * @return null|null
     */
    protected function fireResetChildrenUriEvent($page)
    {
        event('page.resetChildrenUri', [$page]);
    }
}
