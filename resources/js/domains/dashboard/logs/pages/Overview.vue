<template>
    <Suspense>
        <template #default>
            <div class="flex items-center justify-center min-h-screen bg-gray-100">
                <div class="px-16 py-12 mt-4 text-left bg-white shadow-lg">
                    <div class="mt-4">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="class">
                            Selecteer een klas
                        </label>

                        <select v-model="selectedClass"
                            class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3"
                            name="class" id="class">
                            <option value="null" disabled>Selecteer een klas</option>
                            <option v-for="studentClass in classes" :key="studentClass.id" :value="studentClass.id"> {{
                                studentClass.name }}</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="date">
                            Datum</label>

                        <SimpleDatePicker v-model="selectedDate"
                            class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3" />
                    </div>

                    <div class="flex justify-end gap-2 mt-8 mb-4">

                        <button @click="submit"
                            class="middle none center rounded-lg bg-blue-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            :disabled="!selectedDate || !selectedDate">
                            Bekijk
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <template #fallback>
            <h1>Loading...</h1>
        </template>
    </Suspense>
</template>

<script setup lang="ts">
import SimpleDatePicker from 'components/SimpleDatePicker.vue';
import { classStore } from 'domains/dashboard/classes';
import { showModal } from 'services/modal';
import { computed, defineAsyncComponent, ref } from 'vue';

classStore.actions.getAll();
const classes = computed(() => classStore.getters.all.value);

const selectedClass = ref(null);
const selectedDate = ref(undefined);

const submit = () => {
    showModal(defineAsyncComponent(() => import('../components/ShowLogsModal.vue')), {
        class_id: selectedClass.value,
        date: selectedDate.value
    });
}
</script>
