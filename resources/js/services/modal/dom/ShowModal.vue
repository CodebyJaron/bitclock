<template>
    <div ref="modalTemplate" class="fixed inset-0 flex items-center justify-center z-50 modal backdrop-blur-md"
        aria-labelledby="label" aria-hidden="true">
        <div class="modal-dialog" :class="`modal-${modal.size}`">
            <div class="modal-content bg-white shadow-lg rounded-lg">
                <div class="modal-body p-4">
                    <component :is="modal.component" v-bind="modal.props" @hide="close" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { CustomModal, createModal } from './logic';
import { onMounted, ref } from 'vue';
import { ShowModalData } from '../types';

defineProps<{ modal: ShowModalData }>();

const emit = defineEmits<{ (event: 'hide'): void }>();

const modalTemplate = ref<HTMLDivElement>();
const modalBody = ref<HTMLDivElement>();

let bootstrapModal: CustomModal;

const close = () => bootstrapModal.hide();

onMounted(() => {
    if (!modalTemplate.value) return;
    bootstrapModal = createModal(modalTemplate.value, emit);

    if (modalTemplate.value?.children[0].classList.contains('modal-fullscreen') && modalBody.value)
        modalBody.value.style.padding = '0';
});
</script>
