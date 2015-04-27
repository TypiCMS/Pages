@section('js')
    <script src="{{ asset('components/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/admin/form.js') }}"></script>
@stop

@section('otherSideLink')
    @include('core::admin._navbar-public-link')
@stop

@include('core::admin._buttons-form')

{!! BootForm::hidden('id') !!}
{!! BootForm::hidden('position')->value($model->position ? : 0) !!}
{!! BootForm::hidden('parent_id') !!}

<div class="row">

    <div class="col-sm-8">

        @include('core::admin._tabs-lang', ['target' => 'content'])

        <div class="tab-content">

            @foreach ($locales as $lang)
            <div class="tab-pane fade in @if ($locale == $lang)active @endif" id="{{ $lang }}">
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::text(trans('validation.attributes.title'), $lang.'[title]') !!}
                    </div>
                    <div class="col-md-6 form-group @if($errors->has($lang.'.slug'))has-error @endif">
                        <label class="control-label" for="{{ $lang }}[slug]">@lang('validation.attributes.url')</label>
                        <div class="input-group">
                            <span class="input-group-addon">{{ $model->present()->parentUri($lang) }}</span>
                            <input class="form-control" type="text" name="{{ $lang }}[slug]" id="{{ $lang }}[slug]" value="@if($model->hasTranslation($lang)){{ $model->translate($lang)->slug }}@endif" data-slug="{{ $lang }}[title]">
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-slug @if($errors->has($lang.'.slug'))btn-danger @endif" type="button">@lang('validation.attributes.generate')</button>
                            </span>
                        </div>
                        {!! $errors->first($lang.'.slug', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                {!! BootForm::hidden($lang.'[uri]') !!}
                <input type="hidden" name="{{ $lang }}[status]" value="0">
                {!! BootForm::checkbox(trans('validation.attributes.online'), $lang.'[status]') !!}
                {!! BootForm::textarea(trans('validation.attributes.body'), $lang.'[body]')->addClass('ckeditor') !!}
                {!! BootForm::text(trans('validation.attributes.meta_title'), $lang.'[meta_title]') !!}
                {!! BootForm::text(trans('validation.attributes.meta_keywords'), $lang.'[meta_keywords]') !!}
                {!! BootForm::text(trans('validation.attributes.meta_description'), $lang.'[meta_description]') !!}
            </div>
            @endforeach

        </div>

    </div>

    <aside class="col-sm-4">
        <input type="hidden" name="is_home" value="0">
        {!! BootForm::checkbox(trans('validation.attributes.is_home'), 'is_home') !!}
        <input type="hidden" name="private" value="0">
        {!! BootForm::checkbox(trans('validation.attributes.private'), 'private') !!}
        <input type="hidden" name="redirect" value="0">
        {!! BootForm::checkbox(trans('validation.attributes.redirect to first child'), 'redirect') !!}
        @include('core::admin._image-fieldset', ['field' => 'image'])
        @include('core::admin._galleries-fieldset')
        {!! BootForm::select(trans('validation.attributes.module'), 'module', TypiCMS::getModulesForSelect(), null, array('class' => 'form-control')) !!}
        {!! BootForm::select(trans('validation.attributes.template'), 'template', TypiCMS::getPageTemplates(), null, array('class' => 'form-control')) !!}
        @if (! $model->id)
        {!! BootForm::select(trans('validation.attributes.add_to_menu'), 'add_to_menu', ['' => ''] + Menus::all()->lists('title', 'id'), null, array('class' => 'form-control')) !!}
        @endif
        {!! BootForm::textarea(trans('validation.attributes.css'), 'css') !!}
        {!! BootForm::textarea(trans('validation.attributes.js'), 'js') !!}
    </aside>

</div>
