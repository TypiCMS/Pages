@extends('core::admin.master')

@section('title', trans($module.'::global.name'))

@section('main')

<div ng-app="typicms" ng-cloak ng-controller="ListController">

    <a href="{{ route('admin::create-'.str_singular($module)) }}" class="btn-add" title="@lang($module.'::global.New')">
        <i class="fa fa-plus-circle"></i><span class="sr-only">@lang($module.'::global.New')</span>
    </a>

    <h1>
        <span>Pages</span>
    </h1>

    <div class="btn-toolbar">
        @include('core::admin._lang-switcher')
    </div>

    <!-- Nested node template -->
    <div ui-tree="treeOptions">
        <ul ui-tree-nodes="" data-max-depth="3" ng-model="models" id="tree-root">
            <li ng-repeat="model in models" ui-tree-node ng-include="'/views/partials/listItemPage.html'"></li>
        </ul>
    </div>

</div>

@endsection
