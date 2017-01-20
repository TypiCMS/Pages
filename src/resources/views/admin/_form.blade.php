@section('js')
    <script src="{{ asset('components/ckeditor/ckeditor.js') }}"></script>
@endsection

@include('core::admin._buttons-form')

{!! BootForm::hidden('id') !!}
{!! BootForm::hidden('position')->value($model->position ?: 0) !!}
{!! BootForm::hidden('parent_id') !!}

<ul class="nav nav-tabs">
    <li class="active">
        <a href="#tab-content" data-target="#tab-content" data-toggle="tab">@lang('global.Content')</a>
    </li>
    <li>
        <a href="#tab-meta" data-target="#tab-meta" data-toggle="tab">@lang('global.Meta')</a>
    </li>
    <li>
        <a href="#tab-options" data-target="#tab-options" data-toggle="tab">@lang('global.Options')</a>
    </li>
</ul>

<div class="tab-content">

    <div class="tab-pane fade in active" id="tab-content">
        @include('core::admin._image-fieldset', ['field' => 'image'])
        <div class="row">
            <div class="col-md-6">
                {!! TranslatableBootForm::text(__('Title'), 'title') !!}
            </div>
            @foreach ($locales as $lang)
            <div class="col-md-6 form-group form-group-translation @if($errors->has('slug.'.$lang))has-error @endif">
                <label class="control-label" for="slug[{{ $lang }}]"><span>{{ __('Url') }}</span> ({{ $lang }})</label>
                <div class="input-group">
                    <span class="input-group-addon">{{ $model->present()->parentUri($lang) }}</span>
                    <input class="form-control" type="text" name="slug[{{ $lang }}]" id="slug[{{ $lang }}]" value="{{ $model->translate('slug', $lang) }}" data-slug="title[{{ $lang }}]" data-language="{{ $lang }}">
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-slug @if($errors->has('slug.'.$lang))btn-danger @endif" type="button">{{ __('Generate') }}</button>
                    </span>
                </div>
                {!! $errors->first('slug.'.$lang, '<p class="help-block">:message</p>') !!}
            </div>
            @endforeach
        </div>
        {!! TranslatableBootForm::hidden('uri') !!}
        {!! TranslatableBootForm::hidden('status')->value(0) !!}
        {!! TranslatableBootForm::checkbox(__('Online'), 'status') !!}
        {!! TranslatableBootForm::textarea(__('Body'), 'body')->addClass('ckeditor') !!}
        @include('core::admin._galleries-fieldset')
    </div>

    <div class="tab-pane fade" id="tab-meta">
        {!! TranslatableBootForm::text(__('Meta keywords'), 'meta_keywords') !!}
        {!! TranslatableBootForm::text(__('Meta description'), 'meta_description') !!}
    </div>

    <div class="tab-pane fade" id="tab-options">
        {!! BootForm::hidden('is_home')->value(0) !!}
        {!! BootForm::checkbox(__('Is home'), 'is_home') !!}
        {!! BootForm::hidden('private')->value(0) !!}
        {!! BootForm::checkbox(__('Private'), 'private') !!}
        {!! BootForm::hidden('redirect')->value(0) !!}
        {!! BootForm::checkbox(__('Redirect to first child'), 'redirect') !!}
        {!! BootForm::hidden('no_cache')->value(0) !!}
        {!! BootForm::checkbox(__('Don’t generate HTML cache'), 'no_cache') !!}
        @if ($model->children->count())
            {!! BootForm::select(__('Module'), 'module', TypiCMS::getModulesForSelect())->disabled('disabled')->helpBlock(__('pages::global.A page with children cannot be linked to a module')) !!}
        @else
            {!! BootForm::select(__('Module'), 'module', TypiCMS::getModulesForSelect()) !!}
        @endif
        {!! BootForm::select(__('Template'), 'template', TypiCMS::templates())->helpBlock(TypiCMS::getTemplateDir()) !!}
        @if (!$model->id)
        {!! BootForm::select(__('Add to menu'), 'add_to_menu', ['' => ''] + Menus::all()->pluck('name', 'id')->all(), null, array('class' => 'form-control')) !!}
        @endif
        {!! BootForm::textarea(__('Css'), 'css') !!}
        {!! BootForm::textarea(__('Js'), 'js') !!}
    </div>

</div>
