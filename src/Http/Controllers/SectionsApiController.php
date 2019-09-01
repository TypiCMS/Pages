<?php

namespace TypiCMS\Modules\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Filters\FilterOr;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Files\Models\File;
use TypiCMS\Modules\Pages\Facades\Pages;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\Modules\Pages\Models\PageSection;

class SectionsApiController extends BaseApiController
{
    public function index(Page $page, Request $request)
    {
        $data = QueryBuilder::for(PageSection::class)
            ->allowedFilters([
                Filter::custom('title', FilterOr::class),
            ])
            ->allowedIncludes('image')
            ->translated($request->input('translatable_fields'))
            ->where('page_id', $page->id)
            ->paginate($request->input('per_page'));

        return $data;
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

        $this->model->forgetCache();
        Pages::forgetCache();

        return response()->json([
            'error' => !$saved,
        ]);
    }

    public function destroy(Page $page, PageSection $section)
    {
        $deleted = $section->delete();
        Pages::forgetCache();

        return response()->json([
            'error' => !$deleted,
        ]);
    }

    public function files(PageSection $section)
    {
        return $section->files;
    }

    public function attachFiles(PageSection $section, Request $request)
    {
        return $this->model->attachFiles($section, $request);
    }

    public function detachFile(PageSection $section, File $file)
    {
        return $this->model->detachFile($section, $file);
    }
}
