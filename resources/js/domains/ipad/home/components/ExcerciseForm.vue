<template>
    <form @submit.prevent="$emit('submit', editable)">
        <div class="px-8 pt-6 pb-2 mb-4 flex flex-col">
            <div class="-mx-3 md:flex mb-6">
                <div class="md:w-full px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="email">
                        Opdracht
                    </label>
                    <textarea v-model="editable"
                        class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                        id="excercise" placeholder="Ik ga vandaag bezig met PHP" required></textarea>
                    <p class="text-red text-xs italic">Noteer de opdracht waar je vandaag mee bezig gaat.</p>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button type="button"
                    class="middle none center mr-2 rounded-lg bg-red-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-red-500/20 transition-all hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                    @click="$emit('cancel')">
                    Terug
                </button>
                <button type="submit"
                    class="middle none center rounded-lg bg-blue-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                    Check-in
                </button>
            </div>
        </div>
    </form>
</template>

<script setup lang="ts">
import { defineProps, defineEmits, ref } from 'vue';
import { deepCopy } from 'helpers/copy';

const props = defineProps<{ form: string }>();
const editable = ref(deepCopy(props.form));

const emit = defineEmits(['submit', 'cancel']);
</script>
