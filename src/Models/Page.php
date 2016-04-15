<?php

namespace TypiCMS\Modules\Pages\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laracasts\Presenter\PresentableTrait;
use Spatie\Translatable\HasTranslations;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\History\Traits\Historable;
use TypiCMS\NestableTrait;

class Page extends Base
{
    use HasTranslations;
    use Historable;
    use NestableTrait;
    use PresentableTrait;

    protected $presenter = 'TypiCMS\Modules\Pages\Presenters\ModulePresenter';

    protected $guarded = ['id'];

    public $translatable = [
        'title',
        'slug',
        'uri',
        'status',
        'body',
        'meta_keywords',
        'meta_description',
    ];

    protected $appends = ['thumb', 'uri'];

    public $attachments = [
        'image',
    ];

    /**
     * Is this page cacheable?
     *
     * @return bool
     */
    public function cacheable()
    {
        return !$this->no_cache;
    }

    /**
     * Get front office uri.
     *
     * @param string $locale
     *
     * @return string
     */
    public function uri($locale = null)
    {
        $locale = $locale ?: config('app.locale');
        if (!$this->hasTranslation($locale)) {
            return;
        }
        $uri = $this->translate($locale)->uri;
        if (
            config('app.fallback_locale') != $locale ||
            config('typicms.main_locale_in_url')
        ) {
            $uri = $uri ? $locale.'/'.$uri : $locale;
        }

        return $uri ?: '/';
    }

    /**
     * Append thumb attribute.
     *
     * @return string
     */
    public function getThumbAttribute()
    {
        return $this->present()->thumbSrc(null, 22);
    }

    /**
     * Append uri attribute from translation table.
     *
     * @return string uri
     */
    public function getUriAttribute()
    {
        return $this->uri;
    }

    /**
     * A page can have menulinks.
     */
    public function menulinks()
    {
        return $this->hasMany('TypiCMS\Modules\Menulinks\Models\Menulink');
    }

    /**
     * A page has many galleries.
     *
     * @return MorphToMany
     */
    public function galleries()
    {
        return $this->morphToMany('TypiCMS\Modules\Galleries\Models\Gallery', 'galleryable')
            ->withPivot('position')
            ->orderBy('position')
            ->withTimestamps();
    }

    /**
     * A page can have children.
     */
    public function children()
    {
        return $this->hasMany('TypiCMS\Modules\Pages\Models\Page', 'parent_id')->order();
    }

    /**
     * A page can have a parent.
     */
    public function parent()
    {
        return $this->belongsTo('TypiCMS\Modules\Pages\Models\Page', 'parent_id');
    }
}
