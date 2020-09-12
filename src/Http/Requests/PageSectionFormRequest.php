<?php

namespace TypiCMS\Modules\Pages\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class PageSectionFormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'page_id' => 'required|integer',
            'position' => 'nullable|integer',
            'image_id' => 'nullable|integer',
            'title.*' => 'nullable|max:255',
            'slug.*' => 'nullable|alpha_dash|max:255|required_if:status.*,1|required_with:title.*',
            'status.*' => 'boolean',
            'body.*' => 'nullable',
        ];
    }
}
