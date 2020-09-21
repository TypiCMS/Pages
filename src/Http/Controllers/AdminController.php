<?php

namespace TypiCMS\Modules\Pages\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Pages\Http\Requests\FormRequest;
use TypiCMS\Modules\Pages\Models\Page;

class AdminController extends BaseAdminController
{
    public function index(): View
    {
        return view('pages::admin.index');
    }

    public function create(): View
    {
        $model = new Page();

        return view('pages::admin.create')
            ->with(compact('model'));
    }

    public function edit(Page $page): View
    {
        return view('pages::admin.edit')
            ->with(['model' => $page]);
    }

    public function store(FormRequest $request): RedirectResponse
    {
        $page = Page::create($request->validated());

        return $this->redirect($request, $page);
    }

    public function update(Page $page, FormRequest $request): RedirectResponse
    {
        $page->update($request->validated());

        return $this->redirect($request, $page);
    }
}
