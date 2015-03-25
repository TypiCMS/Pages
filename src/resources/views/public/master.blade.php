@extends('core::public.master')

@section('title', $model->title . ' – ' . $websiteTitle)
@section('ogTitle', $model->title)
@section('description', $model->description)
@section('image', url($model->present()->thumbSrc()))
@section('bodyClass', 'body-page body-page-' . $model->id)

@section('css')
    @if($model->css)
    <style type="text/css">
        {{ $model->css }}
    </style>
    @endif
@stop

@section('js')
    @if($model->js)
    <script>
        {{ $model->js }}
    </script>
    @endif
@stop

@section('main')

    @yield('page')

@stop
