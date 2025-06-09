@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-accent-500 text-sm font-medium leading-5 text-primary-900 focus:outline-none focus:border-accent-600 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-primary-600 hover:text-primary-800 hover:border-primary-300 focus:outline-none focus:text-primary-800 focus:border-primary-300 transition duration-150 ease-in-out nav-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
