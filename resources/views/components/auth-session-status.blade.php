@props([
    'status',
])

@if ($status)
    <div {{ $attributes->merge(['class' => 'lc-auth-status']) }}>
        {{ $status }}
    </div>
@endif
