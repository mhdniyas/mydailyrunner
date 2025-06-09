@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-accent-600']) }}>
        {{ $status }}
    </div>
@endif
