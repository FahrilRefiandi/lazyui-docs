@props([
    'selected' => null,
    'showRadio' => true,
])

<div x-data="{ selected: '{{ $selected }}', showRadio: {{ $showRadio ? 'true' : 'false' }} }" {{ $attributes }} {{ $attributes->merge(['class' => 'flex flex-col gap-4']) }}>
    {{ $slot }}
</div>
