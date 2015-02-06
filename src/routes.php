<?php
Route::bind('pages', function ($value) {
    return TypiCMS\Modules\Pages\Models\Page::where('id', $value)
        ->with('translations')
        ->firstOrFail();
});

Route::group(
    array(
        'namespace' => 'TypiCMS\Modules\Pages\Controllers',
        'prefix'    => 'admin',
    ),
    function () {
        Route::resource('pages', 'AdminController');
        Route::post('pages/sort', array('as' => 'admin.pages.sort', 'uses' => 'AdminController@sort'));
    }
);

Route::group(['prefix'=>'api'], function() {
    Route::resource('pages', 'ApiController');
});

Route::group(
    array(
        'before'    => 'visitor.publicAccess',
        'namespace' => 'TypiCMS\Modules\Pages\Controllers',
    ),
    function () {
        Route::get('{uri}', 'PublicController@uri')->where('uri', '(.*)');
    }
);
