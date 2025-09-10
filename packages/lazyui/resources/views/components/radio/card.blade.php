@props(['name', 'value', 'label', 'id' => null, 'selected' => false])

@php
    $id = $id ?? 'radio_' . str_replace(['[', ']'], '', $name) . '_' . $value;
    $isSelected = filter_var($selected, FILTER_VALIDATE_BOOLEAN);
@endphp

<label x-init="@if ($isSelected) selected = '{{ $value }}' @endif" for="{{ $id }}"
{{
    $attributes->twMerge([
        'class' => 'relative h-full w-full items-center justify-between gap-4 p-4 bg-cat-100 border border-transparent cursor-pointer dark:bg-cat-750 rounded-lg hover:bg-cat-200 dark:hover:bg-cat-700 transition-all flex',
    ])
}}
:class="{ 'ring-2 ring-black dark:ring-white dark:bg-cat-700': selected === '{{ $value }}' }">
    <input type="radio" name="{{ $name }}" value="{{ $value }}" id="{{ $id }}" x-model="selected" class="sr-only">
    <div class="flex items-center gap-4">
        {{ $slot }}
    </div>
    <div class="flex items-center" x-show="showRadio">
        <div class="size-4 p-0.5 border-2 rounded-full flex items-center justify-center transition-colors" :class="selected === '{{ $value }}' ? 'border-black dark:border-white' : 'border-cat-800 dark:border-white '">
            <div class="flex w-full h-full rounded-full transition-colors" :class="selected === '{{ $value }}' ? 'bg-black dark:bg-white' : 'bg-transparent'"></div>
        </div>
    </div>
</label>
