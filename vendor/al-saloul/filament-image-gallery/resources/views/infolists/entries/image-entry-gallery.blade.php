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
    $overlap = $isStacked ? $getOverlap() ?? 2 : null;

    $defaultWidth = $getWidth();
    $defaultHeight = $getHeight();

    $defaultWidth = $defaultWidth ? (is_numeric($defaultWidth) ? $defaultWidth . 'px' : $defaultWidth) : 'auto';
    $defaultHeight = $defaultHeight ? (is_numeric($defaultHeight) ? $defaultHeight . 'px' : $defaultHeight) : '150px';

    $galleryId = 'gallery-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());
@endphp

<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div id="{{ $galleryId }}"
        {{ $attributes->merge($getExtraAttributes(), escape: false)->class([
            'fi-in-image',
            'flex items-center',
            match ($overlap) {
                1 => '-space-x-1 rtl:space-x-reverse',
                2 => '-space-x-2 rtl:space-x-reverse',
                3 => '-space-x-3 rtl:space-x-reverse',
                4 => '-space-x-4 rtl:space-x-reverse',
                5 => '-space-x-5 rtl:space-x-reverse',
                6 => '-space-x-6 rtl:space-x-reverse',
                7 => '-space-x-7 rtl:space-x-reverse',
                8 => '-space-x-8 rtl:space-x-reverse',
                default => 'gap-1.5',
            },
        ]) }}
        data-viewer-gallery wire:ignore.self>
        @foreach ($limitedState as $stateItem)
            <img src="{{ $getImageUrl($stateItem) }}"
                style="
                    height: {{ $defaultHeight }};
                    width: {{ $defaultWidth }};
                    cursor: pointer;
                "
                {{ $getExtraImgAttributeBag()->class([
                    'max-w-none object-cover object-center',
                    'rounded-full' => $isCircular,
                    'rounded-lg' => $isSquare,
                    'ring-white dark:ring-gray-900' => $isStacked,
                    'ring-2' => $isStacked && ($overlap === null || $overlap > 0),
                ]) }} />
        @endforeach

        @if ($remaining > 0 && ($limitedRemainingText ?? true))
            <div style="
                    min-height: {{ $defaultHeight }};
                    min-width: {{ $defaultWidth }};
                    height: {{ $defaultHeight }};
                    width: {{ $defaultWidth }};
                "
                @class([
                    'flex items-center justify-center bg-gray-100 font-medium text-gray-500 ring-white dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-900',
                    'rounded-full' => $isCircular,
                    'rounded-lg' => $isSquare,
                    'ring-2' => $isStacked && ($overlap === null || $overlap > 0),
                ])>
                <span class="-ms-0.5 text-xs">
                    +{{ $remaining }}
                </span>
            </div>
        @endif
    </div>

    {{-- Viewer.js assets are loaded dynamically via image-gallery.js --}}
</x-dynamic-component>
