@extends('core::admin.master')

@section('title', __('page_sections::global.name'))

@section('content')

<div ng-cloak ng-controller="ListController">

    <a href="{{ route('admin::index-pages') }}" title="{{ __('pages::global.Back') }}" class="btn-back">
        <span class="text-muted fa fa-arrow-circle-left"></span><span class="sr-only">{{ __('pages::global.Back') }}</span>
    </a>

    <h1>@{{ models.length }} @choice('page_sections::global.page-sections', 2)</h1>

    <div class="btn-toolbar">
        @include('core::admin._button-select')
        @include('core::admin._button-actions')
        @include('core::admin._lang-switcher-for-list')
    </div>

    <div class="table-responsive">

        <table st-persist="pageCategoriesTable" st-table="displayedModels" st-safe-src="models" st-order st-filter class="table table-condensed table-main">
            <thead>
                <tr>
                    <th class="delete"></th>
                    <th class="edit"></th>
                    <th st-sort="status_translated" class="status st-sort">{{ __('Status') }}</th>
                    <th st-sort="image" class="image st-sort">{{ __('Image') }}</th>
                    <th st-sort="position" st-sort-default="true" class="position st-sort">{{ __('Position') }}</th>
                    <th st-sort="title_translated" class="title_translated st-sort">{{ __('Title') }}</th>
                </tr>
            </thead>

            <tbody>
                <tr ng-repeat="model in displayedModels">
                    <td>
                        <input type="checkbox" checklist-model="checked.models" checklist-value="model">
                    </td>
                    <td>
                        @include('core::admin._button-edit', ['permission' => 'update-page_section', 'module' => 'sections'])
                    </td>
                    <td typi-btn-status action="toggleStatus(model)" model="model"></td>
                    <td>
                        <img ng-src="@{{ model.thumb }}" alt="">
                    </td>
                    <td>
                        <input class="form-control input-sm" min="0" type="number" name="position" ng-model="model.position" ng-change="update(model, 'position')">
                    </td>
                    <td>@{{ model.title_translated }}</td>
                </tr>
            </tbody>
        </table>

    </div>

</div>

@endsection
