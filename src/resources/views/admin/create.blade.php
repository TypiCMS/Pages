@extends('core::admin.master')

@section('title', __('pages::global.New'))

@section('content')

    @include('core::admin._button-back', ['module' => 'pages'])
    <h1>
        @lang('pages::global.New')
    </h1>

    {!! BootForm::open()->action(route('admin::index-pages'))->multipart()->role('form') !!}
        @include('pages::admin._form')
    {!! BootForm::close() !!}

    <p class="alert alert-info">{{ __('Save the page then add sections.') }}</p>

@endsection
