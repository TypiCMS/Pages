<?php

namespace TypiCMS\Modules\Pages\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Filters\FilterOr;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Files\Models\File;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\Modules\Pages\Models\PageSection;

class SectionsApiController extends BaseApiController
{
    public function index(Page $page, Request $request): LengthAwarePaginator
    {
        $data = QueryBuilder::for(PageSection::class)
            ->selectFields($request->input('fields.page_sections'))
            ->allowedSorts(['status_translated', 'position', 'title_translated'])
            ->allowedFilters([
                AllowedFilter::custom('title', new FilterOr()),
            ])
            ->allowedIncludes(['image'])
            ->where('page_id', $page->id)
            ->paginate($request->input('per_page'));

        return $data;
    }

    protected function updatePartial(Page $page, PageSection $section, Request $request): JsonResponse
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
            $section->$key = $value;
        }
        $saved = $section->save();

        return response()->json([
            'error' => !$saved,
        ]);
    }

    public function destroy(Page $page, PageSection $section): JsonResponse
    {
        $deleted = $section->delete();

        return response()->json([
            'error' => !$deleted,
        ]);
    }

    public function files(PageSection $section): Collection
    {
        return $section->files;
    }

    public function attachFiles(PageSection $section, Request $request): JsonResponse
    {
        return $section->attachFiles($request);
    }

    public function detachFile(PageSection $section, File $file): void
    {
        $section->detachFile($file);
    }
}
