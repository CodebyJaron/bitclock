<template>
    <Suspense>
        <template #default>
            <form @submit.prevent="$emit('submit', editable)">
                <div class="px-8 pt-6 pb-2 mb-4 flex flex-col">
                    <div class="mb-6">
                        <div class="flex gap-4">
                            <div class="w-full md:w-3/8 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="name">
                                    Volledige naam
                                </label>
                                <input v-model="editable.name"
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                    id="name" type="text" placeholder="Jaron De Klein" required />
                            </div>
                        </div>
                    </div>
                    <div class="mb-6">
                        <div class="w-full">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                for="email">
                                E-mail
                            </label>
                            <input v-model="editable.email"
                                class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3"
                                id="email" type="email" placeholder="jdeklein2@st.noorderpoort.nl" required />
                        </div>
                    </div>

                    <div v-if="classes.length > 0" class="mb-6">
                        <div class="md:w-full">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                for="class">
                                Klas
                            </label>

                            <select v-model="editable.student_class_id"
                                class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3"
                                name="class" id="class">
                                <option value="null" disabled>Selecteer een klas</option>
                                <option v-for="studentClass in classes" :key="studentClass.id" :value="studentClass.id"> {{
                                    studentClass.name }}</option>
                            </select>

                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="email">
                            Info
                        </label>

                        <textarea v-model="editable.info"
                            class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                            id="name" type="text"
                            placeholder="Inteek door bob. Goeie programmeur kan heel goed coderen. Doet mee aan skills"
                            required></textarea>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="button"
                            class="middle none center mr-2 rounded-lg bg-red-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-red-500/20 transition-all hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            @click="$emit('cancel')">
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
        </template>
        <template #fallback>
            <h1>Loading...</h1>
        </template>
    </Suspense>
</template>

<script setup lang="ts">
import { New } from 'types/generics';
import { computed, onMounted, ref } from 'vue';
import { deepCopy } from 'helpers/copy';
import { Student } from '../types';
import { StudentClass } from 'domains/dashboard/classes/types';
import { classStore } from 'domains/dashboard/classes';

defineEmits<{
    (event: 'submit', data: New<Student>): void;
    (event: 'cancel'): void;
}>();

const props = defineProps<{ form: New<Student> }>();

const editable = ref(deepCopy(props.form));

const isFormValid = computed(() => {
    return !!editable.value.name && !!editable.value.email;
});

const classes = ref([] as StudentClass[]);

onMounted(async () => {
    await classStore.actions.getAll();
    classes.value = classStore.getters.all.value;
});
</script>
