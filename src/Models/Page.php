<?php
namespace TypiCMS\Modules\Pages\Models;

use App;
use Config;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use TypiCMS\Models\Base;
use TypiCMS\NestableCollection;
use TypiCMS\Presenters\PresentableTrait;
use TypiCMS\Traits\Historable;

class Page extends Base
{

    use Historable;
    use Translatable;
    use PresentableTrait;

    protected $presenter = 'TypiCMS\Modules\Pages\Presenters\ModulePresenter';

    protected $fillable = array(
        'meta_robots_no_index',
        'meta_robots_no_follow',
        'position',
        'parent_id',
        'is_home',
        'css',
        'js',
        'template',
        'image',
        // Translatable columns
        'title',
        'slug',
        'uri',
        'status',
        'body',
        'meta_title',
        'meta_keywords',
        'meta_description',
    );

    /**
     * Translatable model configs.
     *
     * @var array
     */
    public $translatedAttributes = array(
        'title',
        'slug',
        'uri',
        'status',
        'body',
        'meta_title',
        'meta_keywords',
        'meta_description',
    );

    protected $appends = ['status', 'title', 'thumb', 'uri'];

    /**
     * Columns that are file.
     *
     * @var array
     */
    public $attachments = array(
        'image',
    );

    /**
     * Get public uri
     *
     * @return string
     */
    public function getPublicUri($preview = false, $index = false, $lang = null)
    {
        $lang = $lang ? : App::getLocale() ;

        $indexUri = '/' . $lang;
        if (
            ! Config::get('typicms.langChooser') &&
            Config::get('app.fallback_locale') == $lang &&
            ! Config::get('app.main_locale_in_url')
        ) {
            $indexUri = '/';
        }

        if ($index || $this->is_home) {
            return $indexUri;
        }

        // If model is offline and we are not in preview mode
        if (! $preview && ! $this->translate($lang)->status) {
            return $indexUri;
        }

        if ($this->translate($lang)->uri) {
            return '/' . $this->translate($lang)->uri;
        }
    }

    /**
     * Get uri attribute from translation table
     *
     * @return string uri
     */
    public function getUriAttribute($value)
    {
        return $this->uri;
    }

    /**
     * A page can have menulinks
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
     * A page can have children
     */
    public function children()
    {
        return $this->hasMany('TypiCMS\Modules\Pages\Models\Page', 'parent_id');
    }

    /**
     * A page can have a parent
     */
    public function parent()
    {
        return $this->belongsTo('TypiCMS\Modules\Pages\Models\Page', 'parent_id');
    }

    /**
     * Pages are nestable
     *
     * @return NestableCollection object
     */
    public function newCollection(array $models = array())
    {
        return new NestableCollection($models, 'parent_id');
    }
}
