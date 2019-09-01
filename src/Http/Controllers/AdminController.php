<?php

namespace TypiCMS\Modules\Pages\Http\Controllers;

use Illuminate\Http\Request;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Menus\Facades\Menulinks;
use TypiCMS\Modules\Pages\Http\Requests\FormRequest;
use TypiCMS\Modules\Pages\Models\Page;

class AdminController extends BaseAdminController
{
    /**
     * List models.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('pages::admin.index');
    }

    /**
     * Create form for a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = new;

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
        $data = $request->all();
        $data['parent_id'] = null;
        $page = ::create($data);
        Menulinks::forgetCache();

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
        $data = $request->all();
        $data['parent_id'] = $data['parent_id'] ?: null;
        ::update($page->id, $data);
        event('page.resetChildrenUri', [$page]);
        Menulinks::forgetCache();

        return $this->redirect($request, $page);
    }
}
