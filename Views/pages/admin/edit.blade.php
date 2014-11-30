@section('main')

    {{ BootForm::open()->put()->action(route('admin.pages.update', $model->id))->multipart()->role('form') }}
    {{ BootForm::bind($model) }}
        @include('pages.admin._form')
    {{ BootForm::close() }}

@stop
