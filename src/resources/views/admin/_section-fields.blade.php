<div class="section well">
    {!! Form::hidden('allsections['.$key.'][id]')->disable($disabled ?? false) !!}
    {!! Form::hidden('allsections['.$key.'][page_id]')->value($model->id)->disable($disabled ?? false) !!}
    @foreach ($locales as $locale)
    <div class="form-group form-group-translation @if($errors->has('allsections.'.$key.'.title.'.$locale))has-error @endif">
        <label class="control-label" for="title[{{ $locale }}]">
            <span>{{ __('Title') }}</span> <span>({{ $locale }})</span>
        </label>
        {!! Form::text('allsections['.$key.'][title]['.$locale.']')->data('language', $locale)->class('form-control')->disable($disabled ?? false) !!}
        {!! $errors->first('allsections.'.$key.'.title.'.$locale, '<p class="help-block">:message</p>') !!}
    </div>
    <div class="checkbox form-group-translation">
        <label class="control-label">
            {!! Form::hidden('allsections['.$key.'][status]['.$locale.']')->value(0)->disable($disabled ?? false) !!}
            {!! Form::checkbox('allsections['.$key.'][status]['.$locale.']')->data('language', $locale)->disable($disabled ?? false) !!}
            <span>{{ __('Published') }}</span> <span>({{ $locale }})</span>
        </label>
    </div>
    <div class="form-group form-group-translation @if($errors->has('allsections.'.$key.'.body.'.$locale))has-error @endif">
        <label class="control-label" for="body[{{ $locale }}]">
            <span>{{ __('Body') }}</span> <span>({{ $locale }})</span>
        </label>
        {!! Form::text('allsections['.$key.'][body]['.$locale.']')->data('language', $locale)->addClass('form-control ckeditor')->disable($disabled ?? false) !!}
        {!! $errors->first('allsections.'.$key.'.body.'.$locale, '<p class="help-block">:message</p>') !!}
    </div>
    @endforeach
</div>
