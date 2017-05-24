@extends('core::admin.master')

@section('title', $model->present()->title)

@section('content')

    <a class="btn-back" href="{{ route('admin::edit-page', $model->page_id) }}" title="{{ __('page_sections::global.Back') }}"><span class="text-muted fa fa-arrow-circle-left"></span><span class="sr-only">{{ __('page_sections::global.Back') }}</span></a>

    <h1 class="@if(!$model->present()->title)text-muted @endif">
        {{ $model->present()->title ?: __('Untitled') }}
    </h1>

    {!! BootForm::open()->put()->action(route('admin::update-page_section', [$model->page_id, $model->id]))->multipart()->role('form') !!}
    {!! BootForm::bind($model) !!}
        @include('pages::admin._form-section')
    {!! BootForm::close() !!}

@endsection
