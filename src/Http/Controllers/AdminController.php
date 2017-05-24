<?php

namespace TypiCMS\Modules\Pages\Http\Controllers;

use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Pages\Http\Requests\FormRequest;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\Modules\Pages\Models\PageSection;
use TypiCMS\Modules\Pages\Repositories\EloquentPage;

class AdminController extends BaseAdminController
{
    public function __construct(EloquentPage $page)
    {
        parent::__construct($page);
    }

    /**
     * List models.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $models = $this->repository->allNested([
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
        ]);
        app('JavaScript')->put('models', $models);

        return view('pages::admin.index');
    }

    /**
     * Create form for a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = $this->repository->createModel();
        app('JavaScript')->put('model', $model);

        return view('pages::admin.create')
            ->with(compact('model'));
    }

    /**
     * Edit form for the specified resource.
     *
     * @param \TypiCMS\Modules\Pages\Models\Page $page
     *
     * @return \Illuminate\View\View
     */
    public function edit(Page $page)
    {
        $page->allsections = $page->sections->toArray();
        app('JavaScript')->put('model', $page);

        return view('pages::admin.edit')
            ->with(['model' => $page]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Pages\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FormRequest $request)
    {
        $this->storeSections($request->allsections);
        $data = $request->except('allsections');
        $data['parent_id'] = null;
        $page = $this->repository->create($data);

        return $this->redirect($request, $page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \TypiCMS\Modules\Pages\Models\Page               $page
     * @param \TypiCMS\Modules\Pages\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Page $page, FormRequest $request)
    {
        $this->storeSections($request->allsections);
        $data = $request->except('allsections');
        $data['parent_id'] = $data['parent_id'] ?: null;
        $this->repository->update($page->id, $data);
        event('page.resetChildrenUri', [$page]);

        return $this->redirect($request, $page);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \TypiCMS\Modules\Pages\Models\Page $page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Page $page)
    {
        $deleted = $this->repository->delete($page);

        return response()->json([
            'error' => !$deleted,
        ]);
    }

    /**
     * List models.
     *
     * @return \Illuminate\View\View
     */
    public function files(Page $page)
    {
        $data = [
            'models' => $page->files,
        ];

        return response()->json($data, 200);
    }

    /**
     * Store page sections.
     *
     * @param array|null $data
     *
     * @return null
     */
    private function storeSections($sections)
    {
        if (is_null($sections)) {
            return;
        }
        foreach ($sections as $key => $item) {
            $section = PageSection::firstOrCreate(['id' => $item['id']]);
            $slug = [];
            foreach ($item['title'] as $locale => $title) {
                $slug[$locale] = str_slug($title);
            }
            $section->page_id = $item['page_id'];
            $section->position = $key + 1;
            $section->status = $item['status'];
            $section->title = $item['title'];
            $section->slug = $slug;
            $section->body = $item['body'];
            $section->save();
        }
    }
}
