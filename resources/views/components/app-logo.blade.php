@props([
    'sidebar' => false,
])

@php
    $brandName = config('app.name', 'Linking Circles Academy');
@endphp

@if($sidebar)
    <flux:sidebar.brand :name="$brandName" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center overflow-hidden rounded-md">
            <img src="{{ asset('img/linking-circles-academy-logo.png') }}" alt="{{ $brandName }}" class="h-8 w-8 object-contain" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="$brandName" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center overflow-hidden rounded-md">
            <img src="{{ asset('img/linking-circles-academy-logo.png') }}" alt="{{ $brandName }}" class="h-8 w-8 object-contain" />
        </x-slot>
    </flux:brand>
@endif
