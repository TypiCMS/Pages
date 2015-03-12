<?php
namespace TypiCMS\Modules\Pages\Repositories;

use App;
use Input;
use TypiCMS\Repositories\CacheAbstractDecorator;
use TypiCMS\Services\Cache\CacheInterface;

class CacheDecorator extends CacheAbstractDecorator implements PageInterface
{

    public function __construct(PageInterface $repo, CacheInterface $cache)
    {
        $this->repo = $repo;
        $this->cache = $cache;
    }

    /**
     * Get page by uri
     *
     * @param  string                      $uri
     * @return TypiCMS\Modules\Models\Page $model
     */
    public function getFirstByUri($uri)
    {
        $cacheKey = md5(App::getLocale().'getFirstByUri.'.$uri.implode('.', Input::all()));

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $model = $this->repo->getFirstByUri($uri);

        // Store in cache for next request
        $this->cache->put($cacheKey, $model);

        return $model;
    }


    /**
     * Get submenu for a page
     *
     * @return Collection
     */
    public function getSubMenu($uri, $all = false)
    {
        $cacheKey = md5(App::getLocale().'getSubMenu.'.$uri.$all);

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $models = $this->repo->getSubMenu($uri, $all);

        // Store in cache for next request
        $this->cache->put($cacheKey, $models);

        return $models;
    }

    /**
     * Get pages linked to module to build routes
     *
     * @return array
     */
    public function getForRoutes()
    {
        $cacheKey = md5(App::getLocale() . 'getForRoutes');

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $models = $this->repo->getForRoutes();

        // Store in cache for next request
        $this->cache->put($cacheKey, $models);

        return $models;
    }

    /**
     * Get all uris
     *
     * @return array
     */
    public function allUris()
    {
        return $this->repo->allUris();
    }
}
