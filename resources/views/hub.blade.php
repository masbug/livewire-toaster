<div role="status" id="toaster" x-data="toasterHub" @class([
    'fixed z-50 p-4 w-full flex flex-col pointer-events-none sm:p-6',
    'bottom-0' => $alignment->is('bottom'),
    'top-1/2 -translate-y-1/2' => $alignment->is('middle'),
    'top-0' => $alignment->is('top'),
    'items-start rtl:items-end' => $position->is('left'),
    'items-center' => $position->is('center'),
    'items-end rtl:items-start' => $position->is('right'),
 ])>
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('alpine:init', () => {
            const toasterElement = document.getElementById('toaster');
            Alpine.nextTick(() => {
                const toastData = @json($toasts);
                const toastConfig = @json($config);
                Alpine.$data(toasterElement).initToaster(toastData, toastConfig);
            });

            Alpine.data('toastTemplate', () => ({
                divToast: {
                    ['x-show']() { return this.$data.toast.isVisible; },
                    ['x-init']() { Alpine.nextTick(() => { this.$data.toast.show(this.$el); }); },
                    [':class']() {
                        return this.$data.toast.select({ error: 'text-white', info: 'text-black', success: 'text-white', warning: 'text-white' });
                    },
                },
                divToastMessage: {
                    ['x-text']() { return this.$data.toast.message; },
                    [':class']() {
                        return this.$data.toast.select({ error: 'bg-red-500', info: 'bg-gray-200', success: 'bg-green-600', warning: 'bg-orange-500' });
                    },
                },
                divToastButton: {
                    ['@click']() { this.$data.toast.dispose(); },
                },
            }));
        });
    </script>
    <template x-data="toastTemplate" x-for="toast in toasts" :key="toast.id">
        <div x-bind="divToast"
             @if($alignment->is('bottom'))
             x-transition:enter-start="translate-y-12 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             @elseif($alignment->is('top'))
             x-transition:enter-start="-translate-y-12 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             @else
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             @endif
             x-transition:leave-end="opacity-0 scale-90"
             @class(['relative duration-300 transform transition ease-in-out max-w-xs w-full pointer-events-auto', 'text-center' => $position->is('center')])
        >
            <i x-bind="divToastMessage"
               class="inline-block select-none not-italic px-6 py-3 rounded shadow-lg text-sm w-full {{ $alignment->is('bottom') ? 'mt-3' : 'mb-3' }}"
            ></i>

            @if($closeable)
            <button x-bind="divToastButton" aria-label="@lang('close')" class="absolute right-0 p-2 focus:outline-none rtl:right-auto rtl:left-0 {{ $alignment->is('bottom') ? 'top-3' : 'top-0' }}">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
            @endif
        </div>
    </template>
</div>
