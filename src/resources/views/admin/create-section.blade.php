@extends('core::admin.master')

@section('title', __('page-sections::global.New'))

@section('content')

    @include('core::admin._button-back', ['module' => 'page_sections'])
    <h1>
        @lang('page_sections::global.New')
    </h1>

    {!! BootForm::open()->action(route('admin::store-page_section', $page->id))->multipart()->role('form') !!}
        @include('pages::admin._form-section')
    {!! BootForm::close() !!}

@endsection
