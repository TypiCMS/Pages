<?php

namespace TypiCMS\Modules\Pages\Http\Controllers;

use Illuminate\Http\Request;
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
        $userPreferences = $request->user()->preferences;

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
        ])->map(function ($item) use ($userPreferences) {
            $item->data = $item->toArray();
            $item->isLeaf = $item->module === null ? false : true;
            $item->isExpanded = !array_get($userPreferences, 'Pages_'.$item->id.'_collapsed', false);

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

    public function sort()
    {
        $this->repository->sort(request()->all());

        return response()->json([
            'error' => false,
            'message' => __('Items sorted'),
        ], 200);
    }

    public function detachFile(Page $page, File $file)
    {
        return $this->repository->detachFile($page, $file);
    }

    public function files(Page $page)
    {
        $data = [
            'models' => $page->files,
        ];

        return response()->json($data, 200);
    }
}
