<template>
    <div class="d-flex">
        <DatePicker :model-value="modelValue" locale="nl" :popover="{ visibility: 'click' }" :input-debounce="200"
            :masks="{ input: 'DD-MM-YYYY' }" @update:model-value="changeDate">
            <template #default="{ inputValue, inputEvents }">
                <div>
                    <input id="datepickerPopover" type="text" placeholder="dd-mm-yyyy" autocomplete="off"
                        class="form-control" :value="inputValue" v-on="inputEvents" />
                </div>
            </template>
        </DatePicker>
    </div>
</template>

<script setup lang="ts">
import 'v-calendar/dist/style.css';
import { DatePicker } from 'v-calendar';

defineProps({
    modelValue: { type: [Date, String], default: null },
});

const formatDate = (date: Date): string => {
    if (!date) return '';

    const day = date.getDate();
    const month = date.getMonth() + 1;

    // eslint-disable-next-line no-magic-numbers
    return `${date.getFullYear()}-${month < 10 ? `0${month}` : month}-${day < 10 ? `0${day}` : day}`;
};

const emits = defineEmits<{
    (event: 'update:modelValue', value: string): void;
}>();

const changeDate = (date: Date) => emits('update:modelValue', formatDate(date));
</script>

<style scoped>
.enlarge {
    flex: 1 0 100%;
    width: 100%;
}
</style>
