@extends('pages::public.master')

@section('site-title')
<h1 class="site-title">@include('core::public._site-title')</h1>
@endsection

@section('page')

    {!! $page->present()->body !!}

    @include('galleries::public._galleries', ['model' => $page])

{{--
    @if($slides = Slides::all() and $slides->count())
        @include('slides::public._slider', ['items' => $slides])
    @endif
--}}
{{--
    @if($latestNews = News::latest(3) and $latestNews->count())
        <div class="container-news">
            <h2>@lang('db.Latest news')</h2>
            @include('news::public._list', ['items' => $latestNews])
            <a href="{{ route($lang.'.news') }}" class="btn btn-default btn-xs">@lang('db.All news')</a>
        </div>
    @endif
--}}
{{--
    @if($incomingEvents = Events::incoming() and $incomingEvents->count())
        <div class="container-events">
            <h3>@lang('db.Incoming events')</h3>
            @include('events::public._list', ['items' => $incomingEvents])
            <a href="{{ route($lang.'.events') }}" class="btn btn-default btn-xs">@lang('db.All events')</a>
        </div>
    @endif
--}}
{{--
    @if($partners = Partners::allBy('homepage', 1) and $partners->count())
        <div class="container-partners">
            <h2><a href="{{ route($lang.'.partners') }}">@lang('db.Partners')</a></h2>
            @include('partners::public._list', ['items' => $partners])
        </div>
    @endif
--}}

@endsection
