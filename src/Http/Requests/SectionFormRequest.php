<?php

namespace TypiCMS\Modules\Pages\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class SectionFormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'title.*' => 'nullable|max:255',
            'slug.*' => 'nullable|alpha_dash|max:255',
        ];
    }
}
