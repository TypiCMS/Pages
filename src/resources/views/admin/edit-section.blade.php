@extends('core::admin.master')

@section('title', $model->present()->title)

@section('content')

    <a class="btn-back" href="{{ route('admin::edit-page', $page->id) }}" title="{{ __('Back to page') }}"><span class="text-muted fa fa-arrow-circle-left"></span><span class="sr-only">{{ __('Back to page') }}</span></a>

    <h1 class="@if (!$model->present()->title)text-muted @endif">
        {{ $model->present()->title ?: __('Untitled') }}
    </h1>

    {!! BootForm::open()->put()->action(route('admin::update-page_section', [$page->id, $model->id]))->multipart()->role('form') !!}
    {!! BootForm::bind($model) !!}
        @include('pages::admin._form-section')
    {!! BootForm::close() !!}

@endsection
