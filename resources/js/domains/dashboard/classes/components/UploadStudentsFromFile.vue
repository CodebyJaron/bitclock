<template>
    <div>
        <form @submit.prevent="submitForm" enctype="multipart/form-data">
            <div class="px-8 pt-6 pb-2 mb-4 flex flex-col">
                <div class="mb-6">
                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold" for="file">
                        Studenten
                    </label>
                    <input type="file" name="file" id="file" class="sr-only" @change="onFileChange" accept=".xls, .xlsx" />
                    <label for="file"
                        class="relative flex min-h-[100px] items-center justify-center rounded-md border border-dashed border-[#e0e0e0] p-6 text-center">
                        <div>
                            <span class="inline-flex rounded py-2 px-7 text-base font-medium text-[#07074D]">
                                Selecteer studenten file (Excel)
                            </span>
                        </div>
                    </label>
                </div>
                <p class="italic mb-2 text-xs" v-if="editable">Selected File: {{ editable.name }}</p>

                <p class="italic mb-2 text-xs">Wil je niet automatisch studenten toevoegen klik dan op cancel.</p>

                <div class="flex justify-end mt-4">
                    <button type="button"
                        class="middle none center mr-2 rounded-lg bg-red-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-red-500/20 transition-all hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        @click="emit('cancel')">
                        Cancel
                    </button>
                    <button type="submit"
                        class="middle none center rounded-lg bg-blue-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        :disabled="!isFormValid">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, defineProps, defineEmits } from 'vue';
import { deepCopy } from 'helpers/copy';

const emit = defineEmits<{
    (event: 'submit', data: File): void;
    (event: 'cancel'): void;
}>();

const props = defineProps<{ data: File }>();
const editable = ref<File | null>(null);

const isFormValid = computed(() => {
    return !!editable.value;
});

const onFileChange = (event: Event) => {
    const inputElement = event.target as HTMLInputElement;
    const selectedFile = inputElement.files?.[0];

    if (selectedFile && (selectedFile.type === 'application/vnd.ms-excel' || selectedFile.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
        editable.value = selectedFile;
    } else {
        editable.value = null;
        alert('Dit is geen excel bestand.');
    }
};

const submitForm = () => {
    if (isFormValid.value && editable.value) {
        emit('submit', editable.value);
    }
};
</script>
