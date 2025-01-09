import { Config } from './config';
import { Toast } from './toast';

export function Hub(Alpine) {
    Alpine.data('toasterHub', () => ({
        config: null,
        _toasts: [],

        get toasts() {
            const toasts = this._toasts.filter(t => ! t.trashed);

            if (this._toasts.length && ! toasts.length) {
                this.$nextTick(() => { this._toasts = []; });
            }

            return toasts;
        },

        initToaster(initialToasts, _config) {
            this.config = Config.fromJson(_config);

            document.addEventListener('toaster:received', event => {
                const toast = Toast.fromJson({ duration: this.config.duration, ...event.detail });

                if (this.config.replace) {
                    this.toasts.filter(t => t.equals(toast)).forEach(t => t.dispose());
                } else if (this.config.suppress && this.toasts.some(t => t.equals(toast))) {
                    return;
                }

                this.show(toast);
            });

            initialToasts.map(Toast.fromJson).forEach(toast => this.show(toast));
        },

        show(toast) {
            toast = Alpine.reactive(toast);
            toast.runAfterDuration(toast => toast.dispose());

            if (this.config.alignment.isTop()) {
                this._toasts.unshift(toast);
            } else {
                this._toasts.push(toast);
            }
        },
    }));
}
