@extends('core::admin.master')

@section('title', __('project-categories::global.New'))

@section('content')

    @include('core::admin._button-back', ['module' => 'project_categories'])
    <h1>
        @lang('project_categories::global.New')
    </h1>

    {!! BootForm::open()->action(route('admin::index-project_categories'))->multipart()->role('form') !!}
        @include('projects::admin._form-category')
    {!! BootForm::close() !!}

@endsection
