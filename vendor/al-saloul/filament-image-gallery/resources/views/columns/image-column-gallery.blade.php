@php
    $state = $getState();

    if ($state instanceof \Illuminate\Support\Collection) {
        $state = $state->all();
    }

    $state = \Illuminate\Support\Arr::wrap($state);

    $limit = $getLimit();
    $limitedState = $limit ? array_slice($state, 0, $limit) : $state;
    $remaining = $limit ? max(0, count($state) - $limit) : 0;

    $isCircular = $isCircular();
    $isSquare = $isSquare();
    $isStacked = $isStacked();
    $overlap = $isStacked ? $getOverlap() ?? 2 : 0;

    $defaultWidth = $getWidth();
    $defaultHeight = $getHeight();

    $defaultWidth = $defaultWidth ? (is_numeric($defaultWidth) ? $defaultWidth . 'px' : $defaultWidth) : 'auto';
    $defaultHeight = $defaultHeight ? (is_numeric($defaultHeight) ? $defaultHeight . 'px' : $defaultHeight) : '40px';

    $galleryId = 'gallery-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());

    // Calculate margin for stacked images using inline styles (Tailwind-safe)
    // Each unit = 0.25rem (4px)
    $stackedMarginValue = $overlap * 0.25;
    $stackedMargin = $isStacked && $overlap > 0 ? "-{$stackedMarginValue}rem" : '0';
@endphp

<div id="{{ $galleryId }}"
    {{ $attributes->merge($getExtraAttributes(), escape: false)->class(['fi-ta-image', 'flex items-center', 'gap-1.5' => !$isStacked]) }}
    data-viewer-gallery wire:ignore.self>
    @foreach ($limitedState as $index => $stateItem)
        <img src="{{ $getImageUrl($stateItem) }}"
            style="
                height: {{ $defaultHeight }};
                width: {{ $defaultWidth }};
                cursor: pointer;
                @if ($isStacked && $index > 0) margin-inline-start: {{ $stackedMargin }}; @endif
            "
            {{ $getExtraImgAttributeBag()->class([
                'max-w-none object-cover object-center',
                'rounded-full' => $isCircular,
                'rounded-lg' => $isSquare,
                'ring-white dark:ring-gray-900' => $isStacked,
                'ring-2' => $isStacked && $overlap > 0,
            ]) }} />
    @endforeach

    @if ($remaining > 0 && ($limitedRemainingText ?? true))
        <div style="
                min-height: {{ $defaultHeight }};
                min-width: {{ $defaultWidth }};
                height: {{ $defaultHeight }};
                width: {{ $defaultWidth }};
                @if ($isStacked) margin-inline-start: {{ $stackedMargin }}; @endif
            "
            @class(['flex items-center justify-center font-medium text-gray-500'])>
            <span class="-ms-0.5 text-xs">
                +{{ $remaining }}
            </span>
        </div>
    @endif
</div>

{{-- Viewer.js assets are loaded dynamically via image-gallery.js --}}
