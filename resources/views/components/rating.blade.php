@props(['value' => 0, 'count' => 0])

<div class="z-rating">
    @for($i = 1; $i <= 5; $i++)
        @if($i <= floor($value))
            <i class="bi bi-star-fill"></i>
        @elseif($i - 0.5 <= $value)
            <i class="bi bi-star-half"></i>
        @else
            <i class="bi bi-star"></i>
        @endif
    @endfor
    @if($count)
        <span>({{ $count }})</span>
    @endif
</div>
