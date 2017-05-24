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
        $models = $this->repository->where('page_id', $page->id)->findAll();
        app('JavaScript')->put('models', $models);

        return view('pages::admin.index-sections');
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

        return view('pages::admin.create-section')
            ->with(compact('model'));
    }

    /**
     * Edit form for the specified resource.
     *
     * @param \TypiCMS\Modules\Pages\Models\PageSection $section
     *
     * @return \Illuminate\View\View
     */
    public function edit(PageSection $section)
    {
        app('JavaScript')->put('model', $section);

        return view('pages::admin.edit-section')
            ->with(['model' => $section]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Pages\Http\Requests\PageSectionFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PageSectionFormRequest $request)
    {
        $section = $this->repository->create($request->all());

        return $this->redirect($request, $section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \TypiCMS\Modules\Pages\Models\PageSection                   $section
     * @param \TypiCMS\Modules\Pages\Http\Requests\PageSectionFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PageSection $section, PageSectionFormRequest $request)
    {
        $this->repository->update($request->id, $request->all());
        Pages::forgetCache();

        return $this->redirect($request, $section);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \TypiCMS\Modules\Pages\Models\PageSection $section
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PageSection $section)
    {
        $deleted = $this->repository->delete($section);

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
