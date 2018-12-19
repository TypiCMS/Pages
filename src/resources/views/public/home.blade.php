@extends('pages::public.master')

@section('site-title')
<h1 class="site-title">@include('core::public._site-title')</h1>
@endsection

@section('page')

    @if ($page->image)
        {!! $page->present()->thumb(200, 200) !!}
    @endif

    {!! $page->present()->body !!}

    @include('files::public._documents', ['model' => $page])
    @include('files::public._images', ['model' => $page])

{{--
    @if ($slides = Slides::all() and $slides->count() > 0)
        @include('slides::public._slider', ['items' => $slides])
    @endif
--}}

{{--
    @if ($latestNews = News::latest(3) and $latestNews->count() > 0)
        <div class="news-container">
            <h3><a href="{{ Route::has($lang.'::index-news') ? route($lang.'::index-news') : '/' }}">@lang('db.Latest news')</a></h3>
            @include('news::public._list', ['items' => $latestNews])
            <a href="{{ Route::has($lang.'::index-news') ? route($lang.'::index-news') : '/' }}" class="btn btn-light btn-xs">@lang('db.All news')</a>
        </div>
    @endif
--}}

{{--
    @if ($upcomingEvents = Events::upcoming() and $upcomingEvents->count() > 0)
        <div class="events-container">
            <h3><a href="{{ Route::has($lang.'::index-events') ? route($lang.'::index-events') : '/' }}">@lang('db.Incoming events')</a></h3>
            @include('events::public._list', ['items' => $upcomingEvents])
            <a href="{{ Route::has($lang.'::index-events') ? route($lang.'::index-events') : '/' }}" class="btn btn-light btn-xs">@lang('db.All events')</a>
        </div>
    @endif
--}}

{{--
    @if ($partners = Partners::allBy('homepage', 1) and $partners->count() > 0)
        <div class="partners-container">
            <h3><a href="{{ Route::has($lang.'::index-partners') ? route($lang.'::index-partners') : '/' }}">@lang('db.Partners')</a></h3>
            @include('partners::public._list', ['items' => $partners])
            <a href="{{ Route::has($lang.'::index-partners') ? route($lang.'::index-partners') : '/' }}" class="btn btn-light btn-xs">@lang('db.All partners')</a>
        </div>
    @endif
--}}

@endsection
