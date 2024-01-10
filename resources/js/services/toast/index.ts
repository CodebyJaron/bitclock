import type {Toast} from './types';

import {createApp, h, ref} from 'vue';

import {registerResponseErrorMiddleware, registerResponseMiddleware} from 'services/http';

import ToastComponent from './Toast.vue';

const TOAST_DURATION = 5000;
const STATUS_INFO = 401;
const TIMEOUT = 300;

const toastContainer = document.createElement('div');
document.body.appendChild(toastContainer);

const toasts = ref<Toast[]>([]);

const hideToastMessage = (toast: Toast) => {
    if (toast.timeoutId) clearTimeout(toast.timeoutId);
    if (toast.show) toast.show = false;

    toast.timeoutId = setTimeout(() => {
        const allToasts = toasts.value;
        const index = allToasts.findIndex(({message}) => message === toast.message);
        allToasts.splice(index, 1);
    }, TIMEOUT);
};

const hideToastMessageAfterDelay = (toast: Toast) => {
    if (toast.timeoutId) clearTimeout(toast.timeoutId);
    toast.timeoutId = setTimeout(() => hideToastMessage(toast), TOAST_DURATION);
};

createApp({
    name: 'ToastContainer',
    setup() {
        return () => {
            const toastMessages = toasts.value.map((toast, index) =>
                h(ToastComponent, {
                    toast,
                    onHide: () => hideToastMessage(toast),
                    key: index,
                }),
            );
            return h(
                'div',
                {
                    class: 'relative z-50',
                    style: 'right: 0; bottom: 0;'
                },
                toastMessages,
            );
        };
    },
}).mount(toastContainer);

const createToast = (toast: Toast) => {
    toasts.value.push(toast);
    hideToastMessageAfterDelay(toast);
};

export const successToast = (message: string) => createToast({message, show: true, variant: 'success'});
export const dangerToast = (message: string) => createToast({message, show: true, variant: 'danger'});
export const infoToast = (message: string) => createToast({message, show: true, variant: 'info'});

registerResponseMiddleware(({data}) => {
    if (data?.message && !data?.id) successToast(data.message);
});

registerResponseErrorMiddleware(({response}) => {
    if (!response) return;
    const {data, status} = response;
    if (!data?.message) return;
    if (status === STATUS_INFO) return infoToast(data.message);
    dangerToast(data.message);
});
