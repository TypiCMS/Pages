<?php

namespace TypiCMS\Modules\Pages\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Files\Models\File;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\NestableCollection;

class ApiController extends BaseApiController
{
    public function index(Request $request): NestableCollection
    {
        $userPreferences = $request->user()->preferences;

        $data = QueryBuilder::for(Page::class)
            ->selectFields($request->input('fields.pages'))
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

    protected function updatePartial(Page $page, Request $request): JsonResponse
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

        return response()->json([
            'error' => !$saved,
        ]);
    }

    public function sort(Request $request): JsonResponse
    {
        $data = $request->all();
        foreach ($data['item'] as $position => $item) {
            $page = Page::find($item['id']);

            $sortData = [
                'position' => (int) $position + 1,
                'parent_id' => $item['parent_id'],
                'private' => $item['private'],
            ];

            $page->update($sortData);

            if ($data['moved'] === $item['id']) {
                event('page.resetChildrenUri', [$page]);
            }
        }

        return response()->json([
            'error' => false,
            'message' => __('Items sorted'),
        ], 200);
    }

    public function destroy(Page $page): JsonResponse
    {
        $deleted = $page->delete();

        return response()->json([
            'error' => !$deleted,
        ]);
    }

    public function files(Page $page): Collection
    {
        return $page->files;
    }

    public function attachFiles(Page $page, Request $request): JsonResponse
    {
        return $page->attachFiles($request);
    }

    public function detachFile(Page $page, File $file): void
    {
        $page->detachFile($file);
    }
}
