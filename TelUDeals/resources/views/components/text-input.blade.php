@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => '
            border-gray-300
            bg-gray-50
            text-gray-800
            placeholder-gray-400
       
            rounded-lg
            shadow-sm
        '
    ]) !!}
>
