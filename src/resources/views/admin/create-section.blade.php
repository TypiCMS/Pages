@extends('core::admin.master')

@section('title', __('New page section'))

@section('content')

    <a class="btn-back" href="{{ route('admin::edit-page', $page->id) }}" title="{{ __('Back to page') }}"><span class="text-muted fa fa-arrow-circle-left"></span><span class="sr-only">{{ __('Back to page') }}</span></a>

    <h1>@lang('New page section')</h1>

    {!! BootForm::open()->action(route('admin::store-page_section', $page->id))->multipart()->role('form') !!}
        @include('pages::admin._form-section')
    {!! BootForm::close() !!}

@endsection
