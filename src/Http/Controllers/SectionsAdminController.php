<?php

namespace TypiCMS\Modules\Pages\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Pages\Http\Requests\PageSectionFormRequest;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\Modules\Pages\Models\PageSection;

class SectionsAdminController extends BaseAdminController
{
    public function create(Page $page): View
    {
        $model = new PageSection();

        return view('pages::admin.create-section')
            ->with(compact('model', 'page'));
    }

    public function edit(Page $page, PageSection $section): View
    {
        return view('pages::admin.edit-section')
            ->with([
                'model' => $section,
                'page' => $page,
            ]);
    }

    public function store(Page $page, PageSectionFormRequest $request): RedirectResponse
    {
        $section = PageSection::create($request->validated());

        return $this->redirect($request, $section);
    }

    public function update(Page $page, PageSection $section, PageSectionFormRequest $request): RedirectResponse
    {
        $section->update($request->validated());

        return $this->redirect($request, $section);
    }
}
