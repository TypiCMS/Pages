<?php
namespace TypiCMS\Modules\Pages\Services\Form;

use Config;
use Input;
use TypiCMS\Modules\Pages\Repositories\PageInterface;
use TypiCMS\Services\Form\AbstractForm;
use TypiCMS\Services\Validation\ValidableInterface;

class PageForm extends AbstractForm
{

    public function __construct(ValidableInterface $validator, PageInterface $page)
    {
        $this->validator = $validator;
        $this->repository = $page;
    }

    /**
     * Create a new item
     * 
     * @param  array  $input
     * @return boolean
     */
    public function save(array $input)
    {
        $input['parent_id'] = null;
        return parent::save($input);
    }

    /**
     * Update an existing item
     * 
     * @param  array  $input
     * @return boolean
     */
    public function update(array $input)
    {
        // add checkboxes data
        $input['rss_enabled']      = Input::get('rss_enabled', 0);
        $input['comments_enabled'] = Input::get('comments_enabled', 0);
        $input['is_home']          = Input::get('is_home', 0);
        $input['parent_id']        = Input::get('parent_id') ? : null ;

        // add relations data (default to empty array)
        $input['galleries'] = Input::get('galleries', []);

        return parent::update($input);
    }
}
