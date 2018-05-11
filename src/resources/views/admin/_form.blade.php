@push('js')
    <script src="{{ asset('components/ckeditor/ckeditor.js') }}"></script>
@endpush

@component('core::admin._buttons-form', ['model' => $model])
@endcomponent

{!! BootForm::hidden('id') !!}
{!! BootForm::hidden('position')->value($model->position ?: 0) !!}
{!! BootForm::hidden('parent_id') !!}

@include('files::admin._files-selector')

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" href="#tab-content" data-target="#tab-content" data-toggle="tab">{{ __('Content') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#tab-meta" data-target="#tab-meta" data-toggle="tab">{{ __('Meta') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#tab-options" data-target="#tab-options" data-toggle="tab">{{ __('Options') }}</a>
    </li>
</ul>

<div class="tab-content">

    <div class="tab-pane fade show active" id="tab-content">
        <div class="row">
            <div class="col-md-6">
                {!! TranslatableBootForm::text(__('Title'), 'title') !!}
            </div>
            <div class="col-md-6">
            @foreach ($locales as $lang)
                <div class="form-group form-group-translation">
                    <label class="control-label" for="slug[{{ $lang }}]"><span>{{ __('Url') }}</span> ({{ $lang }})</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{ $model->present()->parentUri($lang) }}</span>
                        </div>
                        <input class="form-control @if($errors->has('slug.'.$lang))is-invalid @endif" type="text" name="slug[{{ $lang }}]" id="slug[{{ $lang }}]" value="{{ $model->translate('slug', $lang) }}" data-slug="title[{{ $lang }}]" data-language="{{ $lang }}">
                        <span class="input-group-append">
                            <button class="btn btn-outline-secondary btn-slug" type="button">{{ __('Generate') }}</button>
                        </span>
                        {!! $errors->first('slug.'.$lang, '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            @endforeach
            </div>
        </div>
        {!! TranslatableBootForm::hidden('uri') !!}
        <div class="form-group">
            {!! TranslatableBootForm::hidden('status')->value(0) !!}
            {!! TranslatableBootForm::checkbox(__('Published'), 'status') !!}
        </div>
        {!! TranslatableBootForm::textarea(__('Body'), 'body')->addClass('ckeditor') !!}

        @can ('see-all-page_sections')
        @if ($model->id)

        <a href="{{ route('admin::create-page_section', $model->id) }}" title="{{ __('New page section') }}" class="btn-back">
            <span class="fa fa-plus-circle"></span><span class="sr-only">{{ __('New page section') }}</span>
        </a>

        <h1>@{{ models.length }} @lang('Page sections')</h1>

        <div ng-cloak ng-controller="ListController">

            <div class="btn-toolbar">
                @include('core::admin._button-select')
                @include('core::admin._button-actions')
                @include('core::admin._lang-switcher-for-list')
            </div>

            <div class="table-responsive">

                <table st-persist="pageSectionsTable" st-table="displayedModels" st-safe-src="models" st-order st-filter class="table table-main">
                    <thead>
                        <tr>
                            <th class="delete"></th>
                            <th class="edit"></th>
                            <th st-sort="status_translated" class="status st-sort">{{ __('Status') }}</th>
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
                                <input class="form-control form-control-sm" min="0" type="number" name="position" ng-model="model.position" ng-change="update(model, 'position')">
                            </td>
                            <td>@{{ model.title_translated }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>
        @endif
        @endcan

    </div>

    <div class="tab-pane fade" id="tab-meta">
        {!! TranslatableBootForm::text(__('Meta keywords'), 'meta_keywords') !!}
        {!! TranslatableBootForm::text(__('Meta description'), 'meta_description') !!}
    </div>

    <div class="tab-pane fade" id="tab-options">
        <div class="form-group">
            {!! BootForm::hidden('is_home')->value(0) !!}
            {!! BootForm::checkbox(__('Is home'), 'is_home') !!}
            {!! BootForm::hidden('private')->value(0) !!}
            {!! BootForm::checkbox(__('Private'), 'private') !!}
            {!! BootForm::hidden('redirect')->value(0) !!}
            {!! BootForm::checkbox(__('Redirect to first child'), 'redirect') !!}
        </div>
        {!! BootForm::select(__('Module'), 'module', TypiCMS::getModulesForSelect())->disable($model->subpages->count())->helpBlock($model->subpages->count() ? __('A page containing subpages cannot be linked to a module') : '') !!}
        {!! BootForm::select(__('Template'), 'template', TypiCMS::templates())->helpBlock(TypiCMS::getTemplateDir()) !!}
        @if (!$model->id)
        {!! BootForm::select(__('Add to menu'), 'add_to_menu', ['' => ''] + Menus::all()->pluck('name', 'id')->all(), null, array('class' => 'form-control')) !!}
        @endif
        {!! BootForm::textarea(__('Css'), 'css') !!}
        {!! BootForm::textarea(__('Js'), 'js') !!}
    </div>

</div>
