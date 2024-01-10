<template>
    <div :class="color.background + ' ' + color.text" class="fixed right-0 bottom-0 mx-4 flex rounded-lg p-4 mb-4 text-sm"
        role="alert">
        <svg class="w-5 h-5 inline mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                clip-rule="evenodd"></path>
        </svg>
        <div>
            <h3 v-if="toast.variant == 'success'"><span class="font-medium">Gelukt!</span> {{ toast.message }}</h3>
            <h3 v-if="toast.variant == 'danger'"><span class="font-medium">Fout!</span> {{ toast.message }}</h3>
            <h3 v-if="toast.variant == 'info'"><span class="font-medium">Info!</span> {{ toast.message }}</h3>
        </div>
    </div>
</template>


<script setup lang="ts">
import { computed } from '@vue/reactivity';
import type { Toast } from './types';

const props = defineProps<{
    toast: Toast;
}>();

const emit = defineEmits<{
    hide: [void];
}>();

const colors = [
    {
        variant: 'success',
        background: 'bg-green-100',
        text: 'text-green-700'
    },
    {
        variant: 'danger',
        background: 'bg-red-100',
        text: 'text-red-700'
    },
    {
        variant: 'info',
        background: 'bg-blue-100',
        text: 'text-blue-700'
    }
];

const color = computed(() =>
    colors.find((c) => c.variant === props.toast.variant) ?? colors[0]
);
</script>
