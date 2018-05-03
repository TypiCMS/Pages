@extends('core::admin.master')

@section('title', __('Pages'))

@section('content')

@push('js')
<script>
    new Vue({
        el: '#app',
        components: { DraggableTree, SlVueTree },
        data: function () {
            return {
                nodes: TypiCMS.models,
                locale: TypiCMS.content_locale,
            }
        },
        methods: {
            toggleStatus(model) {
                if (model.status[this.locale] == '0') {
                    model.status[this.locale] = '1';
                } else {
                    model.status[this.locale] = '0';
                }
            }
        }
    })
</script>
@endpush

<div id="app">

    @include('core::admin._button-create', ['module' => 'pages'])

    <h1>
        <span>{{ __('Pages') }}</span>
    </h1>

    <div class="btn-toolbar">
        @include('core::admin._lang-switcher-for-list')
    </div>

    <sl-vue-tree v-model="nodes" allowMultiselect="false">

        <template slot="title" slot-scope="{ node }">

            <div @click="deleteFromNested(data)" class="btn btn-xs btn-link">
                <span class="fa fa-remove"></span>
            </div>

            <a class="btn btn-default btn-xs" :href="'pages/'+node.data.id+'/edit'">Edit</a>

            <div class="btn btn-xs btn-link btn-status" @click="toggleStatus(node.data)">
                <span class="fa btn-status-switch" :class="node.data.status[locale] == '1' ? 'fa-toggle-on' : 'fa-toggle-off'"></span>
            </div>

            <div v-if="node.data.private" class="fa fa-lock"></div>

            <div class="title">@{{ node.title[locale] }}</div>

            <div class="label label-warning" :href="node.data.module" v-if="node.data.module">@{{ node.data.module }}</div>

        </template>

        <template slot="toggle" slot-scope="{ node }">
            <div class="disclose fa fa-fw" :class="{'fa-caret-right': !node.isExpanded, 'fa-caret-down': node.isExpanded, hidden: !node.children.length}"></div>
        </template>

    </sl-vue-tree>

{{--
    <draggable-tree :data="nodes" draggable="draggable" cross-tree="cross-tree" space="0" indent="25" :droppable="this.module">

        <div slot-scope="{data, level, store}" class="angular-ui-tree-node">

            <div class="tree-row" v-if="!data.isDragPlaceHolder">
                <div class="disclose fa fa-fw" :class="{'fa-caret-right': !data.open, 'fa-caret-down': data.open, hidden: !data.children.length}" @click="store.toggleOpen(data)"></div>
                <div @click="deleteFromNested(data)" class="btn btn-xs btn-link">
                    <span class="fa fa-remove"></span>
                </div>
                <a class="btn btn-default btn-xs" :href="'pages/'+data.id+'/edit'">Edit</a>
                <div class="btn btn-xs btn-link btn-status" @click="toggleStatus(data)">
                    <span class="fa btn-status-switch" :class="data.status[locale] == '1' ? 'fa-toggle-on' : 'fa-toggle-off'"></span>
                </div>
                <div v-if="data.private" class="fa fa-lock"></div>
                <div class="title">@{{ data.title_translated }}</div>
                <div v-if="data.redirect" class="fa fa-level-down text-muted" title="Redirect to first child"></div>
                <div class="label label-warning" :href="data.module" v-if="data.module">@{{ data.module }}</div>
            </div>

        </div>

    </draggable-tree>
 --}}
</div>

@endsection
