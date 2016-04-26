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
                {!! TranslatableBootForm::text(trans('validation.attributes.title'), 'title') !!}
            </div>
            @foreach ($locales as $lang)
            <div class="col-md-6 form-group form-group-translation @if($errors->has($lang.'.slug'))has-error @endif">
                <label class="control-label" for="{{ $lang }}[slug]"><span>@lang('validation.attributes.url')</span> ({{ $lang }})</label>
                <div class="input-group">
                    <span class="input-group-addon">{{ $model->present()->parentUri($lang) }}</span>
                    <input class="form-control" type="text" name="{{ $lang }}[slug]" id="{{ $lang }}[slug]" value="@if($model->hasTranslation($lang)){{ $model->translate($lang)->slug }}@endif" data-slug="{{ $lang }}[title]" data-language="{{ $lang }}">
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-slug @if($errors->has($lang.'.slug'))btn-danger @endif" type="button">@lang('validation.attributes.generate')</button>
                    </span>
                </div>
                {!! $errors->first($lang.'.slug', '<p class="help-block">:message</p>') !!}
            </div>
            @endforeach
        </div>
        {!! TranslatableBootForm::hidden('uri') !!}
        {!! TranslatableBootForm::hidden('status')->value(0) !!}
        {!! TranslatableBootForm::checkbox(trans('validation.attributes.online'), 'status') !!}
        {!! TranslatableBootForm::textarea(trans('validation.attributes.body'), 'body')->addClass('ckeditor') !!}
        @include('core::admin._galleries-fieldset')
    </div>

    <div class="tab-pane fade" id="tab-meta">
        {!! TranslatableBootForm::text(trans('validation.attributes.meta_keywords'), 'meta_keywords') !!}
        {!! TranslatableBootForm::text(trans('validation.attributes.meta_description'), 'meta_description') !!}
    </div>

    <div class="tab-pane fade" id="tab-options">
        {!! BootForm::hidden('is_home')->value(0) !!}
        {!! BootForm::checkbox(trans('validation.attributes.is_home'), 'is_home') !!}
        {!! BootForm::hidden('private')->value(0) !!}
        {!! BootForm::checkbox(trans('validation.attributes.private'), 'private') !!}
        {!! BootForm::hidden('redirect')->value(0) !!}
        {!! BootForm::checkbox(trans('validation.attributes.redirect to first child'), 'redirect') !!}
        {!! BootForm::hidden('no_cache')->value(0) !!}
        {!! BootForm::checkbox(trans('validation.attributes.don’t generate HTML cache'), 'no_cache') !!}
        @if ($model->children->count())
            {!! BootForm::select(trans('validation.attributes.module'), 'module', TypiCMS::getModulesForSelect())->disabled('disabled')->helpBlock(trans('pages::global.A page with children cannot be linked to a module')) !!}
        @else
            {!! BootForm::select(trans('validation.attributes.module'), 'module', TypiCMS::getModulesForSelect()) !!}
        @endif
        {!! BootForm::select(trans('validation.attributes.template'), 'template', TypiCMS::templates())->helpBlock(TypiCMS::getTemplateDir()) !!}
        @if (!$model->id)
        {!! BootForm::select(trans('validation.attributes.add_to_menu'), 'add_to_menu', ['' => ''] + Menus::all()->pluck('name', 'id')->all(), null, array('class' => 'form-control')) !!}
        @endif
        {!! BootForm::textarea(trans('validation.attributes.css'), 'css') !!}
        {!! BootForm::textarea(trans('validation.attributes.js'), 'js') !!}
    </div>

</div>
