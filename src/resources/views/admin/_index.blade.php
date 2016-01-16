<div ng-app="typicms" ng-cloak ng-controller="ListController">

    <a href="{{ route('admin.' . $module . '.create') }}" class="btn-add"><i class="fa fa-plus-circle"></i><span class="sr-only">New</span></a>
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
