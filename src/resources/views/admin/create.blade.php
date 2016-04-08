@extends('core::admin.master')

@section('title', trans('pages::global.New'))

@section('main')

    @include('core::admin._button-back', ['module' => 'pages'])
    <h1>
        @lang('pages::global.New')
    </h1>

    {!! BootForm::open()->action(route('admin::index-pages'))->multipart()->role('form') !!}
        @include('pages::admin._form')
    {!! BootForm::close() !!}

@endsection
