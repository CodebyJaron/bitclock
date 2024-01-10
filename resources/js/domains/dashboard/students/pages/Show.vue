<template>
    <div class="mb-6">
        <div class="flex justify-center mb-4">
            <h1 class="text-base font-bold leading-6 text-gray-900 text-xl">{{ student.name }}</h1>
        </div>


        <!-- <div class="flex justify-between">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Vandaag</h3>

            <select name="time" id="">
                <option value="">7 dagen</option>
            </select>
        </div> -->

        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">

            <div class="overflow-hidden transition hover:scale-105 rounded-lg bg-gray-100 px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Aantal open minuten</dt>
                <dd class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">{{ formatMinutes(student.minutes_open)
                }}</dd>
            </div>
            <div class="overflow-hidden transition hover:scale-105 rounded-lg bg-gray-100 px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Totaal aantal uren</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">...</dd>
            </div>

            <div class="overflow-hidden transition hover:scale-105 rounded-lg bg-gray-100 px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Hoevaak te laat</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{
                    clockStore.getters.findAllByStudent(student.id).filter(clock => clock.status.toString() ==
                        'te_laat').length }}</dd>
            </div>

            <div class="overflow-hidden transition hover:scale-105 rounded-lg bg-gray-100 px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Hoevaak ziek</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{
                    clockStore.getters.findAllByStudent(student.id).filter(clock => clock.status.toString() ==
                        'active').length }}
                </dd>
            </div>

        </dl>

    </div>
</template>

<script setup lang="ts">
import { clockStore } from 'services/clocks';
import { Student } from '../types';
import { computed } from 'vue';
import { formatMinutes } from 'helpers/time';

const props = defineProps<{ student: Student }>();

clockStore.actions.getAll();
const clocks = computed(() => clockStore.getters.all.value);

</script>
