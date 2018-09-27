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
            <h2>@lang('db.Latest news')</h2>
            @include('news::public._list', ['items' => $latestNews])
            <a href="{{ route($lang.'::index-news') }}" class="btn btn-default btn-xs">@lang('db.All news')</a>
        </div>
    @endif
--}}

{{--
    @if ($upcomingEvents = Events::upcoming() and $upcomingEvents->count() > 0)
        <div class="events-container">
            <h3>@lang('db.Incoming events')</h3>
            @include('events::public._list', ['items' => $upcomingEvents])
            <a href="{{ route($lang.'::index-events') }}" class="btn btn-default btn-xs">@lang('db.All events')</a>
        </div>
    @endif
--}}

{{--
    @if ($partners = Partners::allBy('homepage', 1) and $partners->count() > 0)
        <div class="partners-container">
            <h2><a href="{{ route($lang.'::index-partners') }}">@lang('db.Partners')</a></h2>
            @include('partners::public._list', ['items' => $partners])
        </div>
    @endif
--}}

@endsection
