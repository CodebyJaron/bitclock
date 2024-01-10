<template>
    <form @submit.prevent="$emit('submit', editable)">
        <div class="px-8 pt-6 pb-2 mb-4 flex flex-col">
            <div class="-mx-3 md:flex mb-6">
                <div class="md:w-full px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="name">
                        Volledige naam
                    </label>
                    <input v-model="editable.name"
                        class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                        id="name" type="text" placeholder="Jaron De Klein" required />
                    <p class="text-red text-xs italic">Schrijf de volledige naam.</p>
                </div>
            </div>
            <div class="-mx-3 md:flex mb-6">
                <div class="md:w-full px-3">
                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="email">
                        E-mail
                    </label>
                    <input v-model="editable.email"
                        class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3"
                        id="email" type="email" placeholder="jarondeklein@ziggo.nl" required />
                    <p class="text-grey-dark text-xs italic">Dit is van belang! Hier wordt de mail naar toe gestuurd.</p>
                </div>
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

<script setup lang="ts">
import { New } from 'types/generics';
import { User } from '../types';
import { computed, ref } from 'vue';
import { deepCopy } from 'helpers/copy';

defineEmits<{
    (event: 'submit', data: New<User>): void;
    (event: 'cancel'): void;
}>();

const props = defineProps<{ form: New<User> }>();

const editable = ref(deepCopy(props.form));

const isFormValid = computed(() => {
    return !!editable.value.name && !!editable.value.email;
});
</script>
