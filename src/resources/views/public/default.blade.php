@extends('pages::public.master')

@section('page')

    @if ($children->count() > 0)
    <ul class="nav nav-subpages">
        @foreach ($children as $child)
        @include('pages::public._list-item', ['child' => $child])
        @endforeach
    </ul>
    @endif

    @empty(!$page->body)
    <div class="rich-content">{!! $page->present()->body !!}</div>
    @endempty

    @include('files::public._documents', ['model' => $page])
    @include('files::public._images', ['model' => $page])

    @if ($page->publishedSections->count() > 0)
    <div class="page-sections">
        @foreach ($page->publishedSections as $section)
        <div class="page-section" id="{{ $section->position.'-'.$section->slug }}">
            <h2 class="page-section-title">{{ $section->title }}</h2>
            {!! $section->present()->body !!}
        </div>
        @endforeach
    </div>
    @endif
@endsection
