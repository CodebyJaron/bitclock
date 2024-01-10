
<template>
    <Suspense>
        <template #default>
            <form @submit.prevent="$emit('submit', editable)">
                <div class="px-8 pt-6 pb-2 flex flex-col">
                    <div class="flex gap-4">
                        <div class="w-full md:w-3/8 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="name">
                                Inklok
                            </label>
                            <input v-model="editable.inclock_time"
                                class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                id="name" type="time" step="1" placeholder="8:30:00" required />
                        </div>
                        <div class="w-full md:w-3/8 md:mb-0">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                for="uitklok">
                                Uitklok
                            </label>

                            <input v-model="editable.outclock_time"
                                class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                id="uitklok" type="time" step="1" placeholder="15:00:00" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button"
                        class="middle none center mr-2 rounded-lg bg-red-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-red-500/20 transition-all hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        @click="$emit('cancel')">
                        Cancel
                    </button>
                    <button type="submit"
                        class="middle none center rounded-lg bg-blue-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                        Save
                    </button>
                </div>
            </form>
        </template>
        <template #fallback>
            <h1>Loading...</h1>
        </template>
    </Suspense>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { deepCopy } from 'helpers/copy';
import { Clock } from 'services/clocks/types';

defineEmits<{
    (event: 'submit', data: Clock): void;
    (event: 'cancel'): void;
}>();

const props = defineProps<{ form: Clock }>();

const editable = ref(deepCopy(props.form));

</script>
