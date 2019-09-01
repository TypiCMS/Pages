<?php

namespace TypiCMS\Modules\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Files\Models\File;
use TypiCMS\Modules\Pages\Models\Page;

class ApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $userPreferences = $request->user()->preferences;

        $data = QueryBuilder::for(Page::class)
            ->translated($request->input('translatable_fields'))
            ->orderBy('position')
            ->get()
            ->map(function ($item) use ($userPreferences) {
                $item->data = $item->toArray();
                $item->isLeaf = $item->module === null ? false : true;
                $item->isExpanded = !Arr::get($userPreferences, 'Pages_'.$item->id.'_collapsed', false);

                return $item;
            })
            ->childrenName('children')
            ->nest();

        return $data;
    }

    protected function updatePartial(Page $page, Request $request)
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

        $this->model->forgetCache();

        return response()->json([
            'error' => !$saved,
        ]);
    }

    public function sort()
    {
        $this->model->sort(request()->all());

        return response()->json([
            'error' => false,
            'message' => __('Items sorted'),
        ], 200);
    }

    public function destroy(Page $page)
    {
        $deleted = $page->delete();

        return response()->json([
            'error' => !$deleted,
        ]);
    }

    public function files(Page $page)
    {
        return $page->files;
    }

    public function attachFiles(Page $page, Request $request)
    {
        return $this->model->attachFiles($page, $request);
    }

    public function detachFile(Page $page, File $file)
    {
        return $this->model->detachFile($page, $file);
    }
}
