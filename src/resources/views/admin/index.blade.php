@extends('core::admin.master')

@section('title', __('Pages'))

@section('content')

<item-list-tree
    url-base="/api/pages"
    title="Pages"
>

    <template slot="add-button">
        @include('core::admin._button-create', ['url' => route('admin::create-page'), 'module' => 'pages'])
    </template>

    <template slot="buttons">
        @include('core::admin._lang-switcher-for-list')
    </template>

</item-list-tree>

@endsection
