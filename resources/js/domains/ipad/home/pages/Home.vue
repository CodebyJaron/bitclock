<template>
    <Suspense>
        <template #default>
            <div class="flex items-center justify-center min-h-screen bg-gray-100">
                <div class="px-16 py-12 mt-4 text-left bg-white shadow-lg">
                    <h3 class="text-2xl font-bold text-center">BitClock</h3>
                    <div v-if="selectedClass == null" class="mt-4">
                        <label
                            class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                            for="class"
                        >
                            Klas
                        </label>

                        <select
                            v-model="selectedClass"
                            class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3"
                            name="class"
                            id="class"
                        >
                            <option value="null" disabled>Selecteer een klas</option>
                            <option v-for="studentClass in classes" :key="studentClass.id" :value="studentClass.id">
                                {{ studentClass.name }}
                            </option>
                        </select>
                    </div>

                    <div v-if="selectedClass" class="mt-4">
                        <label
                            class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                            for="class"
                        >
                            Klas
                        </label>

                        <select
                            v-model="selectedStudent"
                            class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3"
                            name="class"
                            id="class"
                        >
                            <option value="null" disabled>Selecteer een student</option>
                            <option
                                v-for="student in students.filter(student => student.student_class_id == selectedClass)"
                                :key="student.id"
                                :value="student.id"
                            >
                                {{ student.name }}
                            </option>
                        </select>
                    </div>

                    <div class="flex justify-between gap-2 mt-4 mb-4">
                        <button
                            @click="submitCheckIn"
                            class="middle none center rounded-lg bg-blue-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            :disabled="!selectedStudent"
                        >
                            Check-in
                        </button>
                        <button
                            @click="submitCheckOut"
                            class="middle none center rounded-lg bg-blue-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            :disabled="!selectedStudent"
                        >
                            Check-out
                        </button>
                    </div>

                    <p v-if="selectedClass" class="italic text-xs">
                        Geselecteerde klas: {{ classStore.getters.byId(selectedClass).value.name }}
                    </p>
                </div>
            </div>
        </template>

        <template #fallback>
            <h1>Loading...</h1>
        </template>
    </Suspense>
</template>

<script setup lang="ts">
import {classStore} from 'domains/dashboard/classes';
import {studentStore} from 'domains/dashboard/students';
import {computed, defineAsyncComponent, onMounted, ref} from 'vue';
import {dangerToast, infoToast, successToast} from 'services/toast';
import {confirmModal, formModal, showModal} from 'services/modal';
import {clockStore} from 'services/clocks';
import {goToOverviewPage} from 'services/router';
import {PUBLIC_HOME_DOMAIN_NAME} from '..';
import {canCheckout, getCurrentTime} from 'helpers/time';
import {is_late, getToLateTime} from 'helpers/clockHelpers';

classStore.actions.getAll();
const classes = computed(() => classStore.getters.all.value);

const selectedClass = ref(null);
const selectedStudent = ref(null);
const choosedExcercise = ref('');

studentStore.actions.getAll();
const students = computed(() => studentStore.getters.all.value.filter(student => student.active));

clockStore.actions.getAll();
const clocks = computed(() => clockStore.getters.all.value);

const submitCheckIn = async () => {
    if (clockStore.getters.findTodayByStudent(selectedStudent.value)) {
        if (clockStore.getters.findTodayByStudent(selectedStudent.value)?.outclock_time) {
            return infoToast('Je hebt je al in & uit geklokt voor vandaag.');
        }

        return infoToast(
            `Je bent al ingeklokt om ${clockStore.getters.findTodayByStudent(selectedStudent.value)?.inclock_time}.`,
        );
    }

    formModal(
        choosedExcercise.value,
        defineAsyncComponent(() => import('../components/ExcerciseForm.vue')),
        async (excercise: string) => {
            if (selectedStudent.value == null) {
                return dangerToast('Er ging iets fout');
            }

            const clock = await clockStore.actions.clockIn(selectedStudent.value, excercise);
            if (clock && is_late(clock)) {
                await studentStore.actions.addMinutesToStudent(
                    studentStore.getters.byId(selectedStudent.value).value,
                    getToLateTime(clock),
                );
                showModal(
                    defineAsyncComponent(() => import('../components/LateModal.vue')),
                    {minuten: getToLateTime(clock)},
                );
            }
            successToast('Je bent ingecheckt!');
            selectedClass.value == null;
            selectedStudent.value == null;

            setTimeout(() => {
                location.reload();
            }, 3500);
        },
    );
};

const submitCheckOut = async () => {
    if (selectedStudent.value == null) {
        return dangerToast('Er ging iets fout.');
    }

    await clockStore.actions.clockOut(selectedStudent.value);
    selectedClass.value == null;
    selectedStudent.value == null;

    setTimeout(() => {
        location.reload();
    }, 3500);
};
</script>
