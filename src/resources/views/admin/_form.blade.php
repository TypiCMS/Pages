@section('js')
    <script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
    <script src="{{ asset('js/admin/form.js') }}"></script>
@stop

@section('otherSideLink')
    @include('core::admin._navbar-public-link')
@stop

@include('core::admin._buttons-form')

{!! BootForm::hidden('id') !!}
{!! BootForm::hidden('position')->value($model->position ? : 0) !!}
{!! BootForm::hidden('parent_id') !!}

<ul class="nav nav-tabs">
    <li class="active">
        <a href="#tab-main" data-target="#tab-main" data-toggle="tab">@lang('global.Content')</a>
    </li>
    <li>
        <a href="#tab-files" data-target="#tab-files" data-toggle="tab">@lang('global.Files')</a>
    </li>
    <li>
        <a href="#tab-meta" data-target="#tab-meta" data-toggle="tab">@lang('global.Meta')</a>
    </li>
    <li>
        <a href="#tab-options" data-target="#tab-options" data-toggle="tab">@lang('global.Options')</a>
    </li>
</ul>

<div class="tab-content">

    {{-- Main tab --}}
    <div class="tab-pane fade in active" id="tab-main">

        @include('core::admin._tabs-lang-form', ['target' => 'content'])

        <div class="tab-content">

        @foreach ($locales as $lang)

            <div class="tab-pane fade in @if ($locale == $lang)active @endif" id="content-{{ $lang }}">

                <div class="row">

                    <div class="col-md-6">
                        {!! BootForm::text(trans('validation.attributes.title'), $lang.'[title]') !!}
                    </div>
                    <div class="col-md-6 form-group @if($errors->has($lang.'.slug'))has-error @endif">
                        <label class="control-label" for="{{ $lang }}[slug]">@lang('validation.attributes.url')</label>
                        <div class="input-group">
                            <span class="input-group-addon">{{ $model->present()->parentUri($lang) }}</span>
                            <input class="form-control" type="text" name="{{ $lang }}[slug]" id="{{ $lang }}[slug]" value="@if($model->hasTranslation($lang)){{ $model->translate($lang)->slug }}@endif">
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-slug @if($errors->has($lang.'.slug'))btn-danger @endif" type="button">@lang('validation.attributes.generate')</button>
                            </span>
                        </div>
                        {!! $errors->first($lang.'.slug', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                {!! BootForm::hidden($lang.'[uri]') !!}

                {!! BootForm::checkbox(trans('validation.attributes.online'), $lang.'[status]') !!}

                {!! BootForm::textarea(trans('validation.attributes.body'), $lang.'[body]')->addClass('editor') !!}
            
            </div>
            
        @endforeach

        </div>

    </div>

    {{-- Galleries tab --}}
    <div class="tab-pane fade in" id="tab-files">

        @include('core::admin._image-fieldset', ['field' => 'image'])

        @include('core::admin._galleries-fieldset')

    </div>

    {{-- Metadata tab --}}
    <div class="tab-pane fade in" id="tab-meta">

        @include('core::admin._tabs-lang-form', ['target' => 'meta'])

        <div class="tab-content">

        {{-- Headers --}}
        @foreach ($locales as $lang)

        <div class="tab-pane fade in @if ($locale == $lang)active @endif" id="meta-{{ $lang }}">

            {!! BootForm::text(trans('validation.attributes.meta_title'), $lang.'[meta_title]') !!}

            {!! BootForm::text(trans('validation.attributes.meta_keywords'), $lang.'[meta_keywords]') !!}

            {!! BootForm::text(trans('validation.attributes.meta_description'), $lang.'[meta_description]') !!}

        </div>

        @endforeach

        </div>

    </div>

    {{-- Options --}}
    <div class="tab-pane fade in" id="tab-options">

        {!! BootForm::checkbox(trans('validation.attributes.is_home'), 'is_home') !!}
        
        {!! BootForm::checkbox(trans('validation.attributes.redirect to first child'), 'redirect') !!}

        {!! BootForm::text(trans('validation.attributes.template'), 'template') !!}

        {!! BootForm::textarea(trans('validation.attributes.css'), 'css') !!}

        {!! BootForm::textarea(trans('validation.attributes.js'), 'js') !!}

    </div>

</div>
