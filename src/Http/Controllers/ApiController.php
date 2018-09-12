<?php

namespace TypiCMS\Modules\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Files\Models\File;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\Modules\Pages\Repositories\EloquentPage;

class ApiController extends BaseApiController
{
    public function __construct(EloquentPage $page)
    {
        parent::__construct($page);
    }

    public function index(Request $request)
    {
        $models = $this->repository->orderBy('position')->findAll([
            'id',
            'parent_id',
            'title',
            'position',
            'status',
            'private',
            'redirect',
            'module',
            'slug',
            'uri',
        ])->map(function ($item) {
            $item->data = $item->toArray();
            $item->isLeaf = $item->module === null ? false : true;

            return $item;
        })->childrenName('children')->nest();

        return $models;
    }

    protected function update(Page $page, Request $request)
    {
        $data = [];
        foreach ($request->all() as $column => $content) {
            if (is_array($content)) {
                foreach ($content as $key => $value) {
                    $data[$column.'->'.$key] = $value;
                }
            } else {
                $data[$column] = $content;
            }
        }

        foreach ($data as $key => $value) {
            $page->$key = $value;
        }
        $saved = $page->save();

        $this->repository->forgetCache();

        return response()->json([
            'error' => !$saved,
        ]);
    }

    public function destroy(Page $page)
    {
        $deleted = $this->repository->delete($page);

        return response()->json([
            'error' => !$deleted,
        ]);
    }

    public function detachFile(Page $page, File $file)
    {
        return $this->repository->detachFile($page, $file);
    }

    /**
     * get files.
     */
    public function files(Page $page)
    {
        $data = [
            'models' => $page->files,
        ];

        return response()->json($data, 200);
    }
}
