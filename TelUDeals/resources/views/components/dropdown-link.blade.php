@props(['danger' => false])

<a {{ $attributes->merge([
    'class' => $danger
        ? 'flex items-center gap-3 w-full px-5 py-3 text-sm font-semibold text-red-600
           hover:bg-red-50 transition rounded-lg'
        : 'flex items-center gap-3 w-full px-5 py-3 text-sm font-medium text-gray-700
           hover:bg-gray-100 transition rounded-lg'
]) }}>
    {{ $slot }}
</a>
