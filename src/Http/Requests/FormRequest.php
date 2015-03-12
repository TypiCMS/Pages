<?php
namespace TypiCMS\Modules\Pages\Http\Requests;

use TypiCMS\Http\Requests\AbstractFormRequest;

class FormRequest extends AbstractFormRequest {

    public function rules()
    {
        $rules = [
            'template' => 'alpha_dash|max:255',
            'image'    => 'image|max:2000|image_size:>=500',
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules[$locale . '.slug'] = [
                'required_with:' . $locale . '.title',
                'required_if:' . $locale . '.status,1',
                'alpha_dash',
                'max:255',
            ];
            $rules[$locale . '.title'] = 'max:255';
            $rules[$locale . '.meta_title'] = 'max:255';
            $rules[$locale . '.meta_keywords'] = 'max:255';
            $rules[$locale . '.meta_description'] = 'max:255';
        }
        return $rules;
    }
}
