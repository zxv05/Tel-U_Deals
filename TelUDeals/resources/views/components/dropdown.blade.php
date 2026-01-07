@props([
    'align' => 'right',
    'width' => '48',
    'contentClasses' => 'py-2 bg-white rounded-xl shadow-xl'
])


@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }">
    {{-- Trigger --}}
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    {{-- Dropdown --}}
    <div
        x-show="open"
        x-transition
        @click.outside="open = false"
        class="absolute z-50 mt-2 {{ $width }} rounded-xl shadow-xl {{ $alignmentClasses }}"
        style="display: none;"
    >
        <div class="rounded-xl ring-1 ring-black/5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
