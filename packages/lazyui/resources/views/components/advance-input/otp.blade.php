@props([
    'length' => 6,
    'name' => 'otp',
    'type' => 'mixed', // Opsi: numeric, string, mixed
    'uppercase' => true // Opsi: true, false
])

<div x-data="otpComponent({{ $length }}, '{{ $name }}', '{{ $type }}', {{ $uppercase ? 'true' : 'false' }})" class="flex items-center space-x-2 rtl:space-x-reverse">
    <input type="hidden" :name="name" :value="otp.join('')">

    <template x-for="(item, index) in otp" :key="index">
        <input
            :type="type === 'numeric' ? 'tel' : 'text'"
            :inputmode="type === 'numeric' ? 'numeric' : 'text'"
            maxlength="1"
            {{ $attributes->merge(['class' => 'w-12 h-12 text-center text-2xl font-semibold  rounded-lg placeholder:text-cat-500 focus:ring-[1.7px] focus:outline-none focus:ring-cat-700 dark:focus:ring-cat-200 focus:border-transparent transition duration-150 ease-in-out bg-white dark:bg-cat-700/10 border border-cat-300 dark:border-cat-700/50 disabled:cursor-not-allowed disabled:opacity-50']) }}
            x-ref="`input_${index}`"
            :value="otp[index]"
            @input="handleInput($event, index)"
            @keydown="handleKeydown($event, index)"
            @paste="handlePaste($event, index)"
        >
    </template>
</div>

@once
<script>
function otpComponent(length, name, type, uppercase) {
    return {
        length: length,
        name: name,
        type: type,
        uppercase: uppercase,
        otp: Array(length).fill(''),

        init() {
            this.$nextTick(() => {
                this.$refs.input_0.focus();
            });
        },

        handleInput(e, index) {
            const input = e.target;
            let value = input.value;

            if (this.uppercase && (this.type === 'string' || this.type === 'mixed')) {
                value = value.toUpperCase();
            }

            let regex;
            switch(this.type) {
                case 'string':
                    regex = /^[a-zA-Z]$/;
                    break;
                case 'mixed':
                    regex = /^[a-zA-Z0-9]$/;
                    break;
                case 'numeric':
                default:
                    regex = /^[0-9]$/;
                    break;
            }

            if (regex.test(value)) {
                this.otp[index] = value;
                input.value = value;
                if (index < this.length - 1) {
                    this.$nextTick(() => {
                        this.$refs[`input_${index + 1}`].focus();
                    });
                }
            } else {
                this.otp[index] = '';
                input.value = '';
            }
        },

        handleKeydown(e, index) {
            if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                this.$nextTick(() => {
                    this.$refs[`input_${index - 1}`].focus();
                });
            }
            else if (e.key === 'ArrowLeft' && index > 0) {
                this.$nextTick(() => this.$refs[`input_${index - 1}`].focus());
            }
            else if (e.key === 'ArrowRight' && index < this.length - 1) {
                this.$nextTick(() => this.$refs[`input_${index + 1}`].focus());
            }
        },

        handlePaste(e, index) {
            e.preventDefault();
            let pasteData = e.clipboardData.getData('text').replace(/\s/g, '').slice(0, this.length - index);

            if (this.uppercase && (this.type === 'string' || this.type === 'mixed')) {
                pasteData = pasteData.toUpperCase();
            }

            let lastFilledIndex = index -1;

            let regex;
            switch(this.type) {
                case 'string': regex = /^[a-zA-Z]$/; break;
                case 'mixed': regex = /^[a-zA-Z0-9]$/; break;
                case 'numeric': default: regex = /^[0-9]$/; break;
            }

            for (let i = 0; i < pasteData.length; i++) {
                const char = pasteData.charAt(i);
                if (index + i < this.length && regex.test(char)) {
                    this.otp[index + i] = char;
                    lastFilledIndex = index + i;
                } else {
                    break;
                }
            }

            const focusIndex = lastFilledIndex < index ? index : lastFilledIndex;
            this.$nextTick(() => {
                this.$refs[`input_${focusIndex}`].focus();
            });
        }
    }
}
</script>
@endonce
