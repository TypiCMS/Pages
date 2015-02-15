<?php
namespace TypiCMS\Modules\Pages\Http\Requests;

use TypiCMS\Http\Requests\AbstractFormRequest;

class FormRequest extends AbstractFormRequest {

    public function rules()
    {
        $rules = [
            'template' => 'alpha_dash|max:255',
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

    /**
     * Sanitize inputs
     * 
     * @return array
     */
    public function sanitize()
    {
        $input = $this->all();

        // Checkboxes
        $input['is_home']   = $this->has('is_home');
        $input['parent_id'] = $this->get('parent_id') ? : null ;

        // add relations data (default to empty array)
        $input['galleries'] = $this->get('galleries', []);

        $this->replace($input);
        return parent::sanitize();
    }
}
