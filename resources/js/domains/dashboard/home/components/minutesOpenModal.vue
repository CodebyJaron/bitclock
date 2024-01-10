<template>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">Naam</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">Klas</th>
                <th class="px-4 py-2" style="background-color:#f8f8f8">Hoeveel minuten open</th>
            </tr>
        </thead>
        <tbody class="text-sm font-normal text-gray-700">
            <tr v-for="student in students" :key="student.id" class="hover:bg-gray-100 border-b border-gray-200 py-10">
                <td class="px-4 py-4">{{ student.name }}</td>
                <td class="px-4 py-4">{{ student.student_class_id }}</td>
                <td class="px-4 py-4">{{ formatMinutes(student.minutes_open) }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script setup lang="ts">
import { Student } from 'domains/dashboard/students/types';
import { formatMinutes } from 'helpers/time';

const props = defineProps<{ students: Student[] }>();

</script>
