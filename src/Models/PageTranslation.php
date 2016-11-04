<?php

namespace TypiCMS\Modules\Pages\Models;

use TypiCMS\Modules\Core\Models\BaseTranslation;

class PageTranslation extends BaseTranslation
{
    protected $fillable = [
        'title',
        'slug',
        'uri',
        'status',
        'body',
        'meta_keywords',
        'meta_description',
    ];

    /**
     * get the parent model.
     */
    public function page()
    {
        return $this->belongsTo('TypiCMS\Modules\Pages\Models\Page');
    }

    public function owner()
    {
        return $this->belongsTo('TypiCMS\Modules\Pages\Models\Page', 'page_id');
    }
}
