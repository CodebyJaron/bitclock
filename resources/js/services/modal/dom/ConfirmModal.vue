<template>
    <div ref="modalTemplate" class="fixed inset-0 flex items-center justify-center z-50 modal backdrop-blur-md"
        aria-labelledby="label" aria-hidden="true">

        <div class="">
            <div class="p-4 bg-white shadow shadow-lg rounded-lg">
                <div class="modal-body">
                    <h5 id="label" class="text-xl font-semibold">{{ modal.message }}</h5>
                </div>
                <div class="flex justify-end bg-white gap-2 p-4">
                    <button class="px-4 py-2 text-gray-600 rounded-md bg-gray-100 hover:bg-gray-200 focus:outline-none"
                        @click="resolve(false)">
                        {{ modal.cancelButtonText }}
                    </button>
                    <button
                        class="px-4 py-2 bg-blue-500 text-white transition hover:scale-105 rounded-md hover:bg-blue-600 focus:outline-none"
                        type="button" @click="resolve(true)">
                        {{ modal.okButtonText }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ConfirmModalData } from '../types';
import { CustomModal, createModal } from './logic';
import { onMounted, ref } from 'vue';

const props = defineProps<{ modal: ConfirmModalData }>();

const emit = defineEmits<{ (event: 'hide'): void }>();

const modalTemplate = ref<HTMLDivElement>();

let customModal: CustomModal;

onMounted(() => {
    if (!modalTemplate.value) return;
    customModal = createModal(modalTemplate.value, emit);
});

const resolve = (confirm: boolean) => {
    props.modal.resolver(confirm);
    customModal.hide();
};
</script>
