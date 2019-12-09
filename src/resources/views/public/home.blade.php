@extends('pages::public.master')

@section('site-title')
<h1 class="site-title">@include('core::public._site-title')</h1>
@endsection

@section('page')

    @if ($page->image)
        <img class="page-image" src="{!! $page->present()->image(200, 200) !!}" alt="">
    @endif

    <div class="rich-content">{!! $page->present()->body !!}</div>

    @include('files::public._documents', ['model' => $page])
    @include('files::public._images', ['model' => $page])

{{--
    @if ($slides = Slides::published()->get() and $slides->count() > 0)
        @include('slides::public._slider', ['items' => $slides])
    @endif
--}}

{{--
    @if ($latestNews = News::published()->order()->take(3)->get() and $latestNews->count() > 0)
        <div class="news-list-container">
            <h3 class="news-list-title"><a href="{{ Route::has($lang.'::index-news') ? route($lang.'::index-news') : '/' }}">@lang('db.Latest news')</a></h3>
            @include('news::public._list', ['items' => $latestNews])
            <a class="news-list-btn-more btn btn-light btn-sm" href="{{ Route::has($lang.'::index-news') ? route($lang.'::index-news') : '/' }}">@lang('db.All news')</a>
        </div>
    @endif
--}}

{{--
    @if ($upcomingEvents = Events::upcoming() and $upcomingEvents->count() > 0)
        <div class="event-list-container">
            <h3 class="event-list-title"><a href="{{ Route::has($lang.'::index-events') ? route($lang.'::index-events') : '/' }}">@lang('db.Upcoming events')</a></h3>
            @include('events::public._list', ['items' => $upcomingEvents])
            <a class="event-list-btn-more btn btn-light btn-sm" href="{{ Route::has($lang.'::index-events') ? route($lang.'::index-events') : '/' }}">@lang('db.All events')</a>
        </div>
    @endif
--}}

{{--
    @if ($partners = Partners::published()->where('homepage', 1)->get() and $partners->count() > 0)
        <div class="partner-list-container">
            <h3 class="partner-list-title"><a href="{{ Route::has($lang.'::index-partners') ? route($lang.'::index-partners') : '/' }}">@lang('db.Partners')</a></h3>
            @include('partners::public._list', ['items' => $partners])
            <a class="partner-list-btn-more btn btn-light btn-sm" href="{{ Route::has($lang.'::index-partners') ? route($lang.'::index-partners') : '/' }}">@lang('db.All partners')</a>
        </div>
    @endif
--}}

@endsection
