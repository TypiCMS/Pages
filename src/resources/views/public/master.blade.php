@extends('core::public.master')

@section('title', $page->title . ' – ' . $websiteTitle)
@section('ogTitle', $page->title)
@section('description', $page->meta_description)
@section('keywords', $page->meta_keywords)
@if ($page->image)
@section('image', url($page->present()->thumbSrc()))
@endif
@section('bodyClass', 'body-page body-page-' . $page->id)

@section('css')
    @if($page->css)
    <style type="text/css">
        {{ $page->css }}
    </style>
    @endif
@stop

@section('js')
    @if($page->js)
    <script>
        {{ $page->js }}
    </script>
    @endif
@stop

@section('main')

    @yield('page')

@stop
