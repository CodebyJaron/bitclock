<template>
    <div v-if="students" class="flex-1 p-4 ml-[20%]">
        <section class="container px-4 mx-auto">
            <div class="mt-6 flex items-center">
                <h1 class="text-xl font-bold text-gray-900 cursor-default">Studenten ({{ students.length }})</h1>

                <div class="flex items-center justify-end ml-auto gap-2">
                    <SearchInput :searchAndPage="searchAndPage" />

                    <div class="relative flex items-center mt-4 md:mt-0">
                        <button @click="createCreateModal"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold p-2 rounded flex items-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col mt-6">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden border border-gray-200 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500">
                                            <button class="flex items-center gap-x-3 focus:outline-none">
                                                <span>Naam</span>
                                                <NameFilterIcon @click="filterOnName = !filterOnName" />
                                            </button>
                                        </th>

                                        <th scope="col"
                                            class="px-12 py-3.5 text-sm font-normal text-center rtl:text-right text-gray-500">
                                            <span>E-mail</span>
                                        </th>

                                        <th scope="col"
                                            class="px-12 py-3.5 text-sm font-normal text-center rtl:text-center text-gray-500">
                                            <button class="flex items-center gap-x-3 focus:outline-none">
                                                <span>Status</span>

                                                <div class="relative">
                                                    <button @click="toggleDropdown"
                                                        class="flex items-center gap-x-3 focus:outline-none">
                                                        <OtherFilterIcon />
                                                    </button>

                                                    <div v-if="dropdownVisible"
                                                        class="absolute z-10 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-lg">

                                                        <div class="p-2">
                                                            <label v-for="option in filterOptions" :key="option.value"
                                                                class="flex items-center space-x-2 cursor-pointer">
                                                                <input type="checkbox" :value="option.value"
                                                                    class="form-checkbox text-blue-500"
                                                                    :checked="selectedOptions.includes(option.value)"
                                                                    @change="toggleOption(option.value)" />
                                                                <span class="text-sm">{{ option.label }}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </button>
                                        </th>

                                        <th scope="col"
                                            class="px-4 py-3.5 text-sm font-normal text-center rtl:text-right text-gray-500">
                                            Acties
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="student in paginatedStudents" :key="student.id">
                                        <td class="px-4 py-2 text-sm font-medium whitespace-nowrap">{{ student.name }}
                                        </td>

                                        <td class="px-4 py-2 text-sm font-medium text-center whitespace-nowrap">
                                            {{ student.email }}</td>

                                        <td
                                            class="px-4 py-2 text-sm font-medium text-center whitespace-nowrap cursor-default">
                                            {{ student.active ? 'Actief' : 'Inactief' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm font-medium text-center whitespace-nowrap">
                                            <div class="inline-flex items-center">
                                                <label class="relative flex cursor-pointer items-center rounded-full p-3"
                                                    for="checkbox" data-ripple-dark="true">
                                                    <input type="checkbox"
                                                        class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-md border border-blue-gray-200 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-blue-500 checked:bg-blue-500 checked:before:bg-blue-500 hover:before:opacity-10"
                                                        id="checkbox" :checked="student.active"
                                                        @change="toggleActive(student)" />
                                                    <div
                                                        class="pointer-events-none absolute top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 text-white opacity-0 transition-opacity peer-checked:opacity-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                            viewBox="0 0 20 20" fill="currentColor" stroke="currentColor"
                                                            stroke-width="1">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </label>
                                            </div>

                                            <button @click="createShowModal(student)" class="px-4 py-4 hover:scale-105">
                                                <EyeIcon class="w-6 h-6" />
                                            </button>

                                            <button @click="createEditModal(student)" class="px-4 py-4 hover:scale-105">
                                                <EditIcon class="w-6 h-6" />

                                            </button>

                                            <button @click="removeUser(student)" class="hover:scale-105">
                                                <TrashIcon class="w-6 h-6" />
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <Pagination :currentPage="searchAndPage.currentPage" :totalPages="totalPages"
                            :previousPage="previousPage" :nextPage="nextPage" :goToPage="goToPage" />

                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup lang="ts">
import { Ref, computed, defineAsyncComponent, ref, watch } from 'vue';
import SearchInput from 'components/inputs/SearchInput.vue';
import Pagination from 'components/table/Pagination.vue';
import NameFilterIcon from 'components/icons/NameFilterIcon.vue';
import { studentStore } from '..';
import { Student } from '../types';
import { confirmModal, formModal, showModal } from 'services/modal';
import { dangerToast, successToast } from 'services/toast';
import { New, Updatable } from 'types/generics';
import TrashIcon from 'components/icons/TrashIcon.vue';
import EditIcon from 'components/icons/EditIcon.vue';
import EyeIcon from 'components/icons/EyeIcon.vue';
import OtherFilterIcon from 'components/icons/OtherFilterIcon.vue';

studentStore.actions.getAll();

const searchAndPage = ref({
    searchQuery: '',
    currentPage: 1,
    perPage: 10,
});

const newStudent = ref<New<Student>>({
    name: '',
    email: '',
    info: '',
    active: true,
    student_class_id: 1,
    minutes_open: 0,
    created_at: Date.now(),
});

const selectedOptions = ref<string[]>([]);
const filterOnName = ref(false);
const dropdownVisible = ref(false);


const students = computed(() => studentStore.getters.all.value);

const filteredStudents = computed(() => {
    const query = searchAndPage.value.searchQuery.toLowerCase().trim();

    return students.value
        .filter(student => student.name.toLowerCase().startsWith(query))
        .filter(student => {
            if (selectedOptions.value.includes('active')) {
                return !student.active;
            }

            if (selectedOptions.value.includes('inactive')) {
                return student.active;
            }
            return true;
        })
        .sort((a, b) => (filterOnName.value ? a.name.localeCompare(b.name) : 0));
});

const totalPages = computed(() => Math.ceil(filteredStudents.value.length / searchAndPage.value.perPage));

const paginatedStudents = computed(() => {
    const start = (searchAndPage.value.currentPage - 1) * searchAndPage.value.perPage;
    const end = start + searchAndPage.value.perPage;
    return filteredStudents.value.slice(start, end);
});

const goToPage = (page: number) => {
    searchAndPage.value.currentPage = page;
};

const previousPage = () => {
    if (searchAndPage.value.currentPage > 1) {
        searchAndPage.value.currentPage--;
    }
};

const nextPage = () => {
    if (searchAndPage.value.currentPage < totalPages.value) {
        searchAndPage.value.currentPage++;
    }
};

const toggleOption = (optionValue: string): void => {
    if (selectedOptions.value.includes(optionValue)) {
        selectedOptions.value = selectedOptions.value.filter((value: string) => value !== optionValue);
    } else {
        selectedOptions.value.push(optionValue);
    }
};

const toggleDropdown = (): void => {
    dropdownVisible.value = !dropdownVisible.value;
};

const toggleActive = async (student: Student) => {
    if (!student) {
        return dangerToast("Er ging iets fout!");
    }

    const updatedStudent: Student = Object.assign({}, student, { active: !student.active });
    await studentStore.actions.update(student.id, updatedStudent);

    successToast("Student aangepast!");
}

const createCreateModal = () => {
    if (studentStore.getters.all.value.length <= 0) {
        return dangerToast("Er zijn geen klassen!");
    }

    formModal(
        newStudent.value,
        defineAsyncComponent(() => import('../components/Form.vue')),
        async (student: New<Student>) => {
            await studentStore.actions.create(student);
            console.log('Student toegevoegd voor BitClock');
        },
    );
};

const createEditModal = (student: Student) => {
    formModal(
        student,
        defineAsyncComponent(() => import('../components/Form.vue')),
        async (editedStudent: Updatable<Student>) => {
            await studentStore.actions.update(student.id, editedStudent);
            successToast('Docent aangepast');
        },
    );
};

const createShowModal = (student: Student) => {
    showModal(defineAsyncComponent(() => import('./Show.vue')),
        { student: student });
}

const removeUser = async (student: Student) => {
    const confirmed = await confirmModal(`Weet je zeker dat je "${student.name}" wilt verwijderen?`, 'Verwijderen');
    if (!confirmed) return;
    studentStore.actions.delete(student.id);
    successToast('Gebruiker verwijderd');
};

const filterOptions = ref([
    {
        label: 'Actief',
        value: 'active',
    },
    {
        label: 'Inactief',
        value: 'inactive',
    },
]);
</script>
