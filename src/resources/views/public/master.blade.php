@extends('core::public.master')

@section('title', $page->title.' – '.$websiteTitle)
@section('ogTitle', $page->title)
@section('description', $page->meta_description)
@section('keywords', $page->meta_keywords)
@if ($page->image)
@section('image', $page->present()->image(1200, 630))
@endif
@section('bodyClass', 'body-page body-page-'.$page->id)

@if ($page->css)
    @push('css')
        <style type="text/css">{{ $page->css }}</style>
    @endpush
@endif

@if ($page->js)
    @push('js')
        <script>{!! $page->js !!}</script>
    @endpush
@endif

@section('content')

    <header class="page-header">

        <div class="page-header-container">
            <h1>{{ $page->title }}</h1>
        </div>

    </header>

    @yield('page')

@endsection
