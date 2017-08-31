<?php

namespace TypiCMS\Modules\Pages\Http\Controllers;

use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Pages\Facades\Pages;
use TypiCMS\Modules\Pages\Http\Requests\PageSectionFormRequest;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\Modules\Pages\Models\PageSection;
use TypiCMS\Modules\Pages\Repositories\EloquentPageSection;

class SectionsAdminController extends BaseAdminController
{
    public function __construct(EloquentPageSection $section)
    {
        parent::__construct($section);
    }

    /**
     * List models.
     *
     * @return \Illuminate\View\View
     */
    public function index(Page $page)
    {
        $id = request('page_id');
        $models = $this->repository->where('page_id', $id)->findAll();

        return response()->json($models, 200);
    }

    /**
     * Create form for a new resource.
     *
     * @param \TypiCMS\Modules\Pages\Models\Page $page
     *
     * @return \Illuminate\View\View
     */
    public function create(Page $page)
    {
        $model = $this->repository->createModel();
        app('JavaScript')->put('model', $model);

        return view('pages::admin.create-section')
            ->with(compact('model', 'page'));
    }

    /**
     * Edit form for the specified resource.
     *
     * @param \TypiCMS\Modules\Pages\Models\Page        $page
     * @param \TypiCMS\Modules\Pages\Models\PageSection $section
     *
     * @return \Illuminate\View\View
     */
    public function edit(Page $page, PageSection $section)
    {
        app('JavaScript')->put('model', $section);

        return view('pages::admin.edit-section')
            ->with([
                'model' => $section,
                'page' => $page,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Pages\Models\Page                          $page
     * @param \TypiCMS\Modules\Pages\Http\Requests\PageSectionFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Page $page, PageSectionFormRequest $request)
    {
        $section = $this->repository->create($request->all());
        Pages::forgetCache();

        return $this->redirect($request, $section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \TypiCMS\Modules\Pages\Models\Page                          $page
     * @param \TypiCMS\Modules\Pages\Models\PageSection                   $section
     * @param \TypiCMS\Modules\Pages\Http\Requests\PageSectionFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Page $page, PageSection $section, PageSectionFormRequest $request)
    {
        $this->repository->update($request->id, $request->all());
        Pages::forgetCache();

        return $this->redirect($request, $section);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \TypiCMS\Modules\Pages\Models\Page        $page
     * @param \TypiCMS\Modules\Pages\Models\PageSection $section
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Page $page, PageSection $section)
    {
        $deleted = $this->repository->delete($section);

        return response()->json([
            'error' => !$deleted,
        ]);
    }

    /**
     * get files.
     */
    public function files(PageSection $section)
    {
        $data = [
            'models' => $section->files,
        ];

        return response()->json($data, 200);
    }
}
