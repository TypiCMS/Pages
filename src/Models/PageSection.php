<?php

namespace TypiCMS\Modules\Pages\Models;

use Laracasts\Presenter\PresentableTrait;
use Spatie\Translatable\HasTranslations;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\Files\Models\File;
use TypiCMS\Modules\History\Traits\Historable;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\Modules\Pages\Presenters\ModulePresenter;

class PageSection extends Base
{
    use HasTranslations;
    use Historable;
    use PresentableTrait;

    protected $presenter = ModulePresenter::class;

    protected $guarded = ['id', 'exit'];

    public $translatable = [
        'title',
        'slug',
        'status',
        'body',
    ];

    protected $appends = ['image', 'title_translated', 'status_translated'];

    /**
     * Append title_translated attribute.
     *
     * @return string
     */
    public function getTitleTranslatedAttribute()
    {
        $locale = config('app.locale');

        return $this->translate('title', config('typicms.content_locale', $locale));
    }

    /**
     * Append status_translated attribute.
     *
     * @return string
     */
    public function getStatusTranslatedAttribute()
    {
        $locale = config('app.locale');

        return $this->translate('status', config('typicms.content_locale', $locale));
    }

    /**
     * Append image attribute.
     *
     * @return string
     */
    public function getImageAttribute()
    {
        return $this->files->first();
    }

    /**
     * A section belongs to a page.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Has many files.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function files()
    {
        return $this->morphToMany(File::class, 'model', 'model_has_files', 'model_id', 'file_id')
            ->orderBy('model_has_files.position');
    }
}
