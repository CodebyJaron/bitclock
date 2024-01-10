<template>
    <div class="flex-1 p-4 ml-[20%] min-h-screen bg-gray-100">
        <h1>Overzicht</h1>
        <div>
            <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Studenten die vandaag aanwezig zijn</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{
                        clockStore.getters.getByDate(getCurrentDate()).filter((clock) => !(clock.status.toString() ==
                            'vrij')).length }} / {{ students.length }} ({{
            Math.round(clockStore.getters.getByDate(getCurrentDate()).filter((clock) =>
                !(clock.status.toString()
                    == 'vrij')).length / students.length * 100) }}%)
                    </dd>
                </div>
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Studenten die te laat zijn</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 cursor-pointer"
                        @click="showLateModal(isLateToday())">{{
                            isLateToday().length }}</dd>
                </div>
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Aantal studenten die uren moeten inhalen</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 cursor-pointer"
                        @click="showMinutesOpenModal(studentStore.getters.all.value.filter((student) => student.minutes_open > 0))">
                        {{
                            studentStore.getters.all.value.filter((student) => student.minutes_open > 0).length }}</dd>
                </div>

                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Klassen die vandaag vrij zijn</dt>
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900 cursor-default"> {{
                        studentClasses.filter((studentClass) => studentClass.day_off == dayOfTheWeek).map((studentClass) =>
                            studentClass.name).join(', ') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</template>

<script setup lang="ts">
import { classStore } from 'domains/dashboard/classes';
import { studentStore } from 'domains/dashboard/students';
import { Student } from 'domains/dashboard/students/types';
import { allClockedInToday, isLateToday } from 'helpers/clockHelpers';
import { getCurrentDate } from 'helpers/time';
import { clockStore } from 'services/clocks';
import { showModal } from 'services/modal';
import { dangerToast, infoToast } from 'services/toast';
import { computed, defineAsyncComponent } from 'vue';

studentStore.actions.getAll();
clockStore.actions.getAll();
classStore.actions.getAll();

const students = computed(() => studentStore.getters.all.value.filter(student => student.active));
const studentClasses = computed(() => classStore.getters.all.value);

const dayOfTheWeek = computed(() => new Date().getDay());

const showLateModal = (students: Student[]) => {
    if (students.length <= 0) {
        return infoToast("Er zijn geen studenten die te laat zijn.");
    }

    showModal(defineAsyncComponent(() => import('../components/showLateModal.vue')), { students });
}

const showMinutesOpenModal = (students: Student[]) => {
    if (students.length <= 0) {
        return infoToast("Er zijn geen studenten die nog moeten inhalen.");
    }

    showModal(defineAsyncComponent(() => import('../components/minutesOpenModal.vue')), { students })
}
</script>
