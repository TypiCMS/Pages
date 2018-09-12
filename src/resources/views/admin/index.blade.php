@extends('core::admin.master')

@section('title', __('Pages'))

@section('content')

<div>

    @include('core::admin._button-create', ['module' => 'pages'])

    <h1>
        <span>{{ __('Pages') }}</span>
    </h1>

    <item-list-tree url="{{ route('api::index-pages') }}">

        <template slot="buttons">
            @include('core::admin._lang-switcher-for-list')
        </template>

    </item-list-tree>

</div>

@endsection
