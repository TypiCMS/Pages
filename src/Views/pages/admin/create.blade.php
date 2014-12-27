@section('main')

    {{ BootForm::open()->action(route('admin.pages.index'))->multipart()->role('form') }}
        @include('pages.admin._form')
    {{ BootForm::close() }}

@stop
