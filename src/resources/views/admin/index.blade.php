@extends('core::admin.master')

@section('title', __('Pages'))

@section('content')

<div>

    <h1>
        <span>{{ __('Pages') }}</span>
    </h1>

    @include('core::admin._button-create', ['module' => 'pages'])

    <item-list-tree url-base="{{ route('api::index-pages') }}">

        <template slot="buttons">
            @include('core::admin._lang-switcher-for-list')
        </template>

    </item-list-tree>

</div>

@endsection
