<template>
    <Suspense>
        <template #default>
            <form @submit.prevent="$emit('submit', editable)" accept="">
                <div class="px-8 pt-6 pb-2 mb-4 flex flex-col">
                    <div class="mb-6">
                        <div class="flex gap-4">
                            <div class="w-full md:w-3/8 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="name">
                                    Klas naam
                                </label>
                                <input v-model="editable.name"
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                    id="name" type="text" placeholder="23SDB" required />
                            </div>
                            <div class="w-full md:w-3/8 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grade">
                                    Leerjaar
                                </label>

                                <input v-model="editable.grade"
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                    id="grade" type="number" placeholder="23SDB" required />
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="email">
                            Info
                        </label>

                        <textarea v-model="editable.info"
                            class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                            id="name" type="text" placeholder="23SDB is super gezellig, extra aandacht aan ..., ..., ..."
                            required></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="email">
                            Docent(en)
                        </label>
                        
                        <!-- @vueform/multiselect -->
                        <multiselect class="appearance-none bg-grey-lighter text-grey-darker" mode="multiple"
                            :options="users.map(user => ({ label: user.name, value: user.id }))" v-model="editable.teachers"
                            searchable placeholder="Selecteer docent(en)">
                            <template v-slot:multiplelabel="{ values }: any">
                                <div class="multiselect-multiple-label">
                                    {{ values.map((value: any) => value.label).join(', ') }}
                                </div>
                            </template>
                        </multiselect>

                    </div>

                    <div class="mb-6">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="email">
                            Vrije dag
                        </label>

                        <select v-model="editable.day_off"
                            class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-3 px-4 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                            <option value="1">Maandag</option>
                            <option value="2">Dinsdag</option>
                            <option value="3">Woensdag</option>
                            <option value="4">Donderdag</option>
                            <option value="5">Vrijdag</option>
                        </select>
                    </div>

                    <div class=" flex justify-end mt-4">
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
            <h1>Loading.</h1>
        </template>
    </Suspense>
</template>

<script setup lang="ts">
import { New } from 'types/generics';
import { computed, onMounted, ref } from 'vue';
import { deepCopy } from 'helpers/copy';
import { StudentClass } from '../types';
import { userStore } from 'domains/dashboard/user';
import { User } from 'domains/dashboard/user/types';
import Multiselect from '@vueform/multiselect';
defineEmits<{
    (event: 'submit', data: New<StudentClass>): void;
    (event: 'cancel'): void;
}>();

const props = defineProps<{ form: New<StudentClass> }>();
const editable = ref(deepCopy(props.form));

const isFormValid = computed(() => {
    return !!editable.value.name && !!editable.value.teachers;
});

const users = ref([] as User[]);

onMounted(async () => {
    await userStore.actions.getAll();
    users.value = userStore.getters.all.value.filter((user) => !user.invite_token);
});
</script>
