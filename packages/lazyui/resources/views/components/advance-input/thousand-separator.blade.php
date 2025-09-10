@props(['name', 'id' => $name, 'value' => null, 'max' => null])

<div class="w-full" x-data="thousandSeparator({{ $value ?? 'null' }}, {{ $max ?? 'null' }})" x-init="init()">
    <input type="hidden" name="{{ $name }}" :value="rawValue">

    <input type="text" id="{{ $id }}" x-ref="input" @input="updateValues($event)" {{ $attributes->merge(['class' => 'appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none scheme-light dark:scheme-dark block w-full px-3 py-2.5 text-sm rounded-md placeholder:text-cat-500 focus:ring-[1.7px] focus:outline-none focus:ring-cat-700 dark:focus:ring-cat-200 focus:border-transparent transition duration-150 ease-in-out bg-white dark:bg-cat-700/10 border border-cat-300 dark:border-cat-700/50 file:border-0 file:bg-transparent file:px-1 file:rounded file:text-sm file:font-medium disabled:cursor-not-allowed disabled:opacity-50']) }}>
</div>
@pushOnce('body')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('thousandSeparator', (initialValue = null, max = null) => ({
                rawValue: null,
                max: max,

                init() {
                    if (initialValue !== null && !isNaN(parseInt(initialValue))) {
                        this.rawValue = parseInt(initialValue, 10);
                    }
                    this.$refs.input.value = this.format(this.rawValue);
                },

                format(number) {
                    if (number === null || typeof number === 'undefined') {
                        return '';
                    }
                    return number.toLocaleString('id-ID');
                },

                updateValues(event) {
                    const cleanValue = event.target.value.replace(/[^0-9]/g, '');
                    let numericValue = cleanValue === '' ? null : parseInt(cleanValue, 10);

                    if (this.max !== null && numericValue > this.max) {
                        numericValue = this.max;
                    }

                    this.rawValue = numericValue;
                    event.target.value = this.format(this.rawValue);
                }
            }));
        });
    </script>
@endPushOnce
