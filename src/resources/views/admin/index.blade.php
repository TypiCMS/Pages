@extends('core::admin.master')

@section('title', __('Pages'))

@section('content')

<div>

    <h2>
        <span>{{ __('Pages') }}</span>
    </h2>

    @include('core::admin._button-create', ['module' => 'pages'])

    <item-list-tree url="{{ route('api::index-pages') }}">

        <template slot="buttons">
            @include('core::admin._lang-switcher-for-list')
        </template>

    </item-list-tree>

</div>

@endsection
