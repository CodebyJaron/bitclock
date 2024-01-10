<template>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">Naam</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">Klas</th>
                <th class="px-4 py-2" style="background-color:#f8f8f8">Hoelaat aanwezig</th>
            </tr>
        </thead>
        <tbody class="text-sm font-normal text-gray-700">
            <tr v-for="student in students" :key="student.id" class="hover:bg-gray-100 border-b border-gray-200 py-10">
                <td class="px-4 py-4">{{ student.name }}</td>
                <td class="px-4 py-4">{{ classStore.getters.byId(student.student_class_id).value.name }}</td>
                <td class="px-4 py-4">{{ clockStore.getters.findTodayByStudent(student.id)?.inclock_time }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script setup lang="ts">
import { classStore } from 'domains/dashboard/classes';
import { Student } from 'domains/dashboard/students/types';
import { clockStore } from 'services/clocks';

clockStore.actions.getAll();
classStore.actions.getAll();

const props = defineProps<{ students: Student[] }>();

</script>
