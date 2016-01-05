<?php

namespace TypiCMS\Modules\Pages\Repositories;

use TypiCMS\Modules\Core\Repositories\RepositoryInterface;

interface PageInterface extends RepositoryInterface
{
    /**
     * Get a page by its uri.
     *
     * @param string $uri
     * @param string $locale
     * @param array  $with
     *
     * @return TypiCMS\Modules\Models\Page $model
     */
    public function getFirstByUri($uri, $locale, array $with = []);

    /**
     * Get submenu for a page.
     *
     * @return Collection
     */
    public function getSubMenu($uri, $all = false);

    /**
     * Get pages linked to module to build routes.
     *
     * @return array
     */
    public function getForRoutes();

    /**
     * Get all translated pages for a select/options.
     *
     * @return array
     */
    public function allForSelect();
}
