{{--
    Komponen input dengan tombol generate yang terintegrasi.
    Dapat dikonfigurasi untuk generate di frontend atau backend.
    Sempurna untuk form create (dengan generate otomatis) dan form edit (dengan nilai awal).

    Atribut yang tersedia:
    - mode: 'frontend' (default) atau 'backend'.
    - length: Angka, panjang kode yang akan digenerate (default: 12).
    - prefix: String, teks awalan untuk kode (default: 'CODE').
    - name: String, atribut 'name' untuk elemen input.
    - value: String|null, nilai awal untuk input, berguna untuk form edit (default: null).
    - generate-on-load: Boolean, jika true & tidak ada 'value' awal, kode akan dibuat saat komponen dimuat (default: false).
    - endpoint: String, URL API untuk mode 'backend'.
    - button-label: String, teks untuk tombol generate (default: 'Generate').
--}}
@props([
    'mode' => 'frontend',
    'length' => 12,
    'prefix' => 'CODE',
    'name' => 'generated_code',
    'value' => null,
    'generateOnLoad' => false,
    'endpoint' => '',
    'buttonLabel' => 'Generate',
])

<div x-data="generateCodeComponent({{ json_encode([
    'mode' => $mode,
    'length' => (int) $length,
    'prefix' => $prefix,
    'value' => $value,
    'generateOnLoad' => (bool) $generateOnLoad,
    'endpoint' => $endpoint,
]) }})" x-init="init()">

    <div class="relative rounded-md">
        <input
            type="text"
            name="{{ $name }}"
            id="{{ $name }}"
            placeholder="Klik generate atau isi manual"
            x-model="codeValue"
            :disabled="isLoading"
            {{ $attributes->twMerge('appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none scheme-light dark:scheme-dark block w-full px-3 py-2.5 text-sm rounded-md placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-[1.7px] focus:outline-none focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition duration-150 ease-in-out bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700/50 file:border-0 file:bg-transparent file:px-1 file:rounded file:text-sm file:font-medium disabled:cursor-not-allowed disabled:opacity-50') }}
        />

        <div class="absolute inset-y-0 right-0 flex items-center pr-2">
            <button type="button" @click="generate()" :disabled="isLoading" class="relative inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors cursor-pointer focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-gray-900 dark:bg-white text-white dark:text-gray-900 shadow hover:bg-gray-800/90 dark:hover:bg-white/90 py-1 px-3">
                <template x-if="isLoading">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <template x-if="!isLoading">
                    <span>{{ $buttonLabel }}</span>
                </template>
            </button>
        </div>
    </div>
</div>

@pushOnce('body')
    <script>
        function generateCodeComponent(options = {}) {
            return {
                config: {
                    mode: options.mode || 'frontend',
                    length: options.length || 12,
                    prefix: options.prefix || 'CODE',
                    generateOnLoad: options.generateOnLoad || false,
                    endpoint: options.endpoint || '',
                },
                codeValue: options.value || '',
                isLoading: false,

                init() {
                    if (this.config.generateOnLoad && !this.codeValue) {
                        this.generate();
                    }
                },

                generate() {
                    if (this.config.mode === 'backend') {
                        this.generateBackend();
                    } else {
                        this.generateFrontend();
                    }
                },

                _createCode() {
                    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    let result = '';
                    const length = this.config.length;
                    for (let i = 0; i < length; i++) {
                        result += characters.charAt(Math.floor(Math.random() * characters.length));
                    }
                    return this.config.prefix ? `${this.config.prefix}-${result}` : result;
                },

                generateFrontend() {
                    this.codeValue = this._createCode();
                },

                async generateBackend() {
                    if (!this.config.endpoint) {
                        console.error('Endpoint URL is not provided for backend mode.');
                        this.codeValue = 'Error: Endpoint not configured.';
                        return;
                    }

                    this.isLoading = true;
                    this.codeValue = 'Generating...';

                    const params = new URLSearchParams({
                        length: this.config.length,
                        prefix: this.config.prefix
                    });
                    const url = `${this.config.endpoint}?${params.toString()}`;

                    try {
                        const response = await fetch(url, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                            },
                        });

                        if (!response.ok) {
                            const errorData = await response.json().catch(() => ({ message: 'Network response was not ok' }));
                            throw new Error(errorData.message);
                        }

                        const data = await response.json();
                        this.codeValue = data.code;

                    } catch (error) {
                        console.error('There was a problem with the fetch operation:', error);
                        this.codeValue = 'Failed to generate code.';
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endPushOnce
