<?php

namespace TypiCMS\Modules\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\Modules\Pages\Models\PageSection;
use TypiCMS\Modules\Pages\Repositories\EloquentPageSection;

class SectionsApiController extends BaseApiController
{
    public function __construct(EloquentPageSection $section)
    {
        parent::__construct($section);
    }

    public function index(Page $page, Request $request)
    {
        $models = QueryBuilder::for(PageSection::class)
            ->allowedFilters('date')
            ->translated(explode(',', $request->input('translatable_fields')))
            ->with('files')
            ->where('page_id', $page->id)
            ->paginate($request->input('per_page'));

        return $models;
    }

    protected function updatePartial(Page $page, PageSection $section, Request $request)
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

        $this->repository->forgetCache();

        return response()->json([
            'error' => !$saved,
        ]);
    }

    public function destroy(Page $page, PageSection $section)
    {
        $deleted = $this->repository->delete($section);

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
