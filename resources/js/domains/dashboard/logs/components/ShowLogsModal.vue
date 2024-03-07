<template>
    <div class="mb-6">
        <div class="flex justify-center mb-4">
            <h1 class="text-base font-bold leading-6 text-gray-900 text-xl">
                {{ classStore.getters.byId(props.class_id).value.name }}
            </h1>
        </div>

        <div class="flex justify-between">
            <h3 class="text-base font-semibold leading-6 text-gray-900">{{ beautifyDate(props.date.toString()) }}</h3>
        </div>

        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div class="overflow-hidden transition hover:scale-105 rounded-lg bg-gray-100 px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Totaal in de klas</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                    {{ classInfo.students.length }}
                </dd>
            </div>

            <div class="overflow-hidden transition hover:scale-105 rounded-lg bg-gray-100 px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Totale aanwezigheid</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                    {{ allClockedInByClocks(clocks, classInfo).length }} / {{ classInfo.students.length }}
                </dd>
            </div>

            <div class="overflow-hidden transition hover:scale-105 rounded-lg bg-gray-100 px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Ziek</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                    {{
                        clocks.filter(
                            clock =>
                                clock.status.toString() === 'ziek' &&
                                classInfo.students.find(student => student.student_class_id == classInfo.id),
                        ).length
                    }}
                </dd>
            </div>
        </dl>
    </div>

    <div class="flex mb-6">
        <section class="w-full">
            <details
                open
                class="w-full bg-gray-100 p-4 rounded-xl shadow-md group mx-auto max-h-[56px] open:!max-h-[400px] transition-[max-height] duration-500 overflow-hidden"
            >
                <summary
                    class="outline-none cursor-pointer font-semibold marker:text-transparent group-open:before:rotate-90 before:origin-center relative before:w-[18px] before:h-[18px] before:transition-transform before:duration-200 before:-left-1 before:top-2/4 before:-translate-y-2/4 before:absolute before:bg-no-repeat before:bg-[length:18px_18px] before:bg-center before:bg-[url('data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20class%3D%22h-6%20w-6%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%20stroke-width%3D%222%22%3E%0A%20%20%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20d%3D%22M9%205l7%207-7%207%22%20%2F%3E%0A%3C%2Fsvg%3E')]"
                >
                    Studenten (Klik)
                </summary>

                <hr class="my-2 scale-x-150" />

                <div class="overflow-y-auto h-72">
                    <table class="table-auto border-collapse w-full">
                        <thead>
                            <tr
                                class="rounded-lg text-sm bg-grey-200 font-medium text-gray-700 text-left"
                                style="font-size: 0.9674rem"
                            >
                                <th class="px-4 py-2 bg-gray-200" style="background-color: #f8f8f8">Naam</th>
                                <th class="px-4 py-2" style="background-color: #f8f8f8">Presentatie</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 bg-white overflow-y-scroll">
                            <tr
                                v-for="student in students"
                                :key="student.id"
                                class="hover:bg-gray-100 border-b border-gray-200 py-10"
                            >
                                <td class="px-4 py-4">{{ student.name }}</td>
                                <td class="px-4 py-4">
                                    {{ clockPresentation(clocks.find(clock => clock.student_id == student.id)) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </details>
        </section>
    </div>
</template>

<script setup lang="ts">
import {classStore} from 'domains/dashboard/classes';
import {studentStore} from 'domains/dashboard/students';
import {allClockedInByClocks, clockPresentation} from 'helpers/clockHelpers';
import {beautifyDate} from 'helpers/date';
import {getCurrentDate} from 'helpers/time';
import {clockStore} from 'services/clocks';
import {computed} from 'vue';

clockStore.actions.getAll();
studentStore.actions.getAll();

const props = defineProps<{class_id: number; date: number}>();

const clocks = computed(() => clockStore.getters.getByDate(props.date.toString()));

const students = computed(() =>
    studentStore.getters.all.value.filter(student => student.student_class_id == props.class_id),
);
const classInfo = computed(() => classStore.getters.byId(props.class_id).value);
</script>
