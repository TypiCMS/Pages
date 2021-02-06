@push('js')
    <script src="{{ asset('components/ckeditor4/ckeditor.js') }}"></script>
    <script src="{{ asset('components/ckeditor4/config-full.js') }}"></script>
@endpush

@component('core::admin._buttons-form', ['model' => $model])
@endcomponent

{!! BootForm::hidden('id') !!}

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" href="#tab-content" data-bs-toggle="tab">{{ __('Content') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#tab-meta" data-bs-toggle="tab">{{ __('Meta') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#tab-options" data-bs-toggle="tab">{{ __('Options') }}</a>
    </li>
</ul>

<div class="tab-content">

    <div class="tab-pane fade show active" id="tab-content">

        <file-manager related-table="{{ $model->getTable() }}" :related-id="{{ $model->id ?? 0 }}"></file-manager>
        <file-field type="image" field="image_id" :init-file="{{ $model->image ?? 'null' }}"></file-field>
        <files-field :init-files="{{ $model->files }}"></files-field>

        <div class="row gx-3">
            <div class="col-md-6">
                {!! TranslatableBootForm::text(__('Title'), 'title') !!}
            </div>
            <div class="col-md-6">
            @foreach ($locales as $lang)
                <div class="mb-3 form-group-translation">
                    <label class="form-label" for="slug[{{ $lang }}]"><span>{{ __('Url') }}</span> ({{ $lang }})</label>
                    <div class="input-group">
                        <span class="input-group-text">{{ $model->present()->parentUri($lang) }}</span>
                        <input class="form-control @if ($errors->has('slug.'.$lang))is-invalid @endif" type="text" name="slug[{{ $lang }}]" id="slug[{{ $lang }}]" value="{{ $model->translate('slug', $lang) }}" data-slug="title[{{ $lang }}]" data-language="{{ $lang }}">
                        <button class="btn btn-outline-secondary btn-slug" type="button">{{ __('Generate') }}</button>
                        {!! $errors->first('slug.'.$lang, '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            @endforeach
            </div>
        </div>
        {!! TranslatableBootForm::hidden('uri') !!}
        <div class="mb-3">
            {!! TranslatableBootForm::hidden('status')->value(0) !!}
            {!! TranslatableBootForm::checkbox(__('Published'), 'status') !!}
        </div>
        {!! TranslatableBootForm::textarea(__('Body'), 'body')->addClass('ckeditor-full') !!}

        @can('read page_sections')
        @if ($model->id)
        <item-list
            url-base="/api/pages/{{ $model->id }}/sections"
            locale="{{ config('typicms.content_locale') }}"
            fields="id,image_id,page_id,position,status,title"
            table="page_sections"
            title="sections"
            include="image"
            appends="thumb"
            :searchable="['title']"
            :sorting="['position']">

            <template slot="add-button" v-if="$can('create page_sections')">
                @include('core::admin._button-create', ['url' => route('admin::create-page_section', $model->id), 'module' => 'page_sections'])
            </template>

            <template slot="columns" slot-scope="{ sortArray }">
                <item-list-column-header name="checkbox" v-if="$can('update page_sections')||$can('delete page_sections')"></item-list-column-header>
                <item-list-column-header name="edit" v-if="$can('update page_sections')"></item-list-column-header>
                <item-list-column-header name="status_translated" sortable :sort-array="sortArray" :label="$t('Status')"></item-list-column-header>
                <item-list-column-header name="position" sortable :sort-array="sortArray" :label="$t('Position')"></item-list-column-header>
                <item-list-column-header name="image" :label="$t('Image')"></item-list-column-header>
                <item-list-column-header name="title_translated" sortable :sort-array="sortArray" :label="$t('Title')"></item-list-column-header>
            </template>

            <template slot="table-row" slot-scope="{ model, checkedModels, loading }">
                <td class="checkbox" v-if="$can('update page_sections')||$can('delete page_sections')"><item-list-checkbox :model="model" :checked-models-prop="checkedModels" :loading="loading"></item-list-checkbox></td>
                <td v-if="$can('update page_sections')">@include('core::admin._button-edit', ['segment' => 'sections', 'module' => 'page_sections'])</td>
                <td><item-list-status-button :model="model"></item-list-status-button></td>
                <td><item-list-position-input :model="model"></item-list-position-input></td>
                <td><img :src="model.thumb" alt="" height="27"></td>
                <td v-html="model.title_translated"></td>
            </template>

        </item-list>
        @endif
        @endcan

    </div>

    <div class="tab-pane fade" id="tab-meta">
        {!! TranslatableBootForm::text(__('Meta keywords'), 'meta_keywords') !!}
        {!! TranslatableBootForm::text(__('Meta description'), 'meta_description') !!}
    </div>

    <div class="tab-pane fade" id="tab-options">
        <div class="mb-3">
            {!! BootForm::hidden('is_home')->value(0) !!}
            {!! BootForm::checkbox(__('Is home'), 'is_home') !!}
            {!! BootForm::hidden('private')->value(0) !!}
            {!! BootForm::checkbox(__('Private'), 'private') !!}
            {!! BootForm::hidden('redirect')->value(0) !!}
            {!! BootForm::checkbox(__('Redirect to first child'), 'redirect') !!}
        </div>
        {!! BootForm::select(__('Module'), 'module', TypiCMS::getModulesForSelect())->disable($model->subpages->count() > 0)->formText($model->subpages->count() ? __('A page containing subpages cannot be linked to a module') : '') !!}
        {!! BootForm::select(__('Template'), 'template', TypiCMS::templates()) !!}
        @if (!$model->id)
        {!! BootForm::select(__('Add to menu'), 'add_to_menu', ['' => ''] + Menus::all()->pluck('name', 'id')->all(), null, ['class' => 'form-control']) !!}
        @endif
        {!! BootForm::textarea(__('Css'), 'css') !!}
        {!! BootForm::textarea(__('Js'), 'js') !!}
    </div>

</div>
