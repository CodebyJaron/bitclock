<template>
    <div ref="modalTemplate" class="fixed inset-0 flex items-center justify-center z-50 modal backdrop-blur-md"
        aria-labelledby="label" aria-hidden="true">
        <div class="modal-dialog" :class="`modal-${modal.size}`">
            <div class="modal-content bg-white shadow-lg rounded-lg">
                <div class="modal-body p-4">
                    <component :is="modal.component" :form="modal.form" @submit="submit" @cancel="close" />
                </div>
            </div>
        </div>
    </div>
</template>


<script setup lang="ts">
import { FormModalData } from '../types';
import { CustomModal, createModal } from './logic';
import { onMounted, ref } from 'vue';

const props = defineProps<{ modal: FormModalData }>();

const emit = defineEmits<{ (event: 'hide'): void }>();

const modalTemplate = ref<HTMLDivElement>();

let customModal: CustomModal;

const close = () => customModal.hide();

onMounted(() => {
    if (!modalTemplate.value) return;
    customModal = createModal(modalTemplate.value, emit);
});

const submit = async (form: unknown) => {
    await props.modal.submitEvent(form);
    close();
};
</script>
