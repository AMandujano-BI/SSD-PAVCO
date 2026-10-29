@forelse($part->plateLayers as $layer)
    {{ $layer->plateType->name }}@if($layer->thickness !== null && $layer->thickness !== '') - {{ $layer->thickness }}@if($layer->thickness_unit == 1) microns @elseif($layer->thickness_unit == 2) mils @endif @endif
    @if(!$loop->last)<br>@endif
@empty
    @if($part->plateType)
        {{ $part->plateType->name }}
        - {{ $part->plateThick }}
        @if($part->typePlateThick == 1) microns @endif
        @if($part->typePlateThick == 2) mils @endif
    @endif
@endforelse
