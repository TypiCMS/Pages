@extends('core::admin.master')

@section('title', __('Pages'))

@section('content')

<div ng-cloak ng-controller="ListController">

    @include('core::admin._button-create', ['module' => 'pages'])

    <h1>
        <span>{{ __('Pages') }}</span>
    </h1>

    <div class="btn-toolbar">
        @include('core::admin._lang-switcher-for-list')
    </div>

    <div ui-tree="treeOptions">
        <ul ui-tree-nodes="" data-max-depth="3" ng-model="models" id="tree-root">
            <li ng-repeat="model in models" ui-tree-node collapsed="treeOptions.collapsed(this)" ng-include="'/views/partials/listItemPage.html'"></li>
        </ul>
    </div>

</div>

@endsection
