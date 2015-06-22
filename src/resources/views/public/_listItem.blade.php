<li id="page_{{ $child->id }}" class="{{ Request::is($child->uri(config('app.locale'))) ? 'active' : '' }}">
    <a href="{{ url($child->uri(config('app.locale'))) }}">
        {{ $child->title }}
    </a>
    @if ($child->items)
        <ul>
            @foreach ($child->items as $childPage)
                @include('pages::public._listItem', array('child' => $childPage))
            @endforeach
        </ul>
    @endif
</li>
