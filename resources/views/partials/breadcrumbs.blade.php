<div class="w-100">

@unless ($breadcrumbs->isEmpty())
<ul class="breadcrumb">
    @foreach ($breadcrumbs as $breadcrumb)

        @if (!is_null($breadcrumb->url) && !$loop->last)
            <li>
                <a href="{{ $breadcrumb->url }}">
                    {{ $breadcrumb->title }}
                </a>
            </li>
        @else
            <li class="active">
                {{ $breadcrumb->title }}
            </li>
        @endif

    @endforeach
</ul>
@endunless

</div>
