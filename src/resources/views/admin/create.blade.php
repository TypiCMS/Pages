@extends('core::admin.master')

@section('title', __('New page'))

@section('content')

    @include('core::admin._button-back', ['module' => 'pages'])
    <h1>
        @lang('New page')
    </h1>

    {!! BootForm::open()->action(route('admin::index-pages'))->multipart()->role('form') !!}
        @include('pages::admin._form')
    {!! BootForm::close() !!}

    <p class="alert alert-info">{{ __('Save this page first, then add sections.') }}</p>

@endsection
