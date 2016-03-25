<?php

namespace TypiCMS\Modules\Pages\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use TypiCMS\Modules\Core\Repositories\RepositoriesAbstract;

class EloquentPage extends RepositoriesAbstract implements PageInterface
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Update an existing model.
     *
     * @param array  Data needed for model update
     *
     * @return bool
     */
    public function update(array $data)
    {
        $model = $this->model->find($data['id']);

        $model->fill($data);

        $this->syncRelation($model, $data, 'galleries');

        if ($model->save()) {
            event('page.resetChildrenUri', [$model]);

            return true;
        }

        return false;
    }

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
        $model = $this->make($with)
            ->whereHas('translations', function (Builder $query) use ($uri, $locale) {
                $query->where('uri', $uri)
                    ->where('locale', $locale);
                if (!Request::input('preview')) {
                    $query->where('status', 1);
                }
            })
            ->firstOrFail();

        return $model;
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
        if (in_array($uri, config('translatable.locales'))) {
            if (isset($rootUriArray[1])) { // i
                $uri .= '/'.$rootUriArray[1]; // add next part of uri in locale
            }
        }

        $query = $this->model
            ->with('translations')
            ->select('*')
            ->addSelect('pages.id AS id')
            ->join('page_translations', 'pages.id', '=', 'page_translations.page_id')
            ->where('uri', '!=', $uri)
            ->where('uri', 'LIKE', $uri.'%');

        if (!$all) {
            $query->where('status', 1);
        }
        $query->where('locale', config('app.locale'));

        $models = $query->order()->get()->nest();

        return $models;
    }

    /**
     * Get pages linked to a module.
     *
     * @return array
     */
    public function getForRoutes()
    {
        $pages = $this->make(['translations'])
            ->where('module', '!=', '')
            ->get()
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
            'position'  => $position,
            'parent_id' => $item['parent_id'],
        ];
    }

    /**
     * Get all translated pages for a select/options.
     *
     * @return array
     */
    public function allForSelect()
    {
        $pages = $this->all([], true)
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
     * @return void|null
     */
    protected function fireResetChildrenUriEvent($page)
    {
        event('page.resetChildrenUri', [$page]);
    }
}
