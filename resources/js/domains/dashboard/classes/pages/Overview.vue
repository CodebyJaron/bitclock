<template>
    <div class="">
        <div v-if="classes" class="flex-1 p-4 ml-[20%]">
            <section class="container px-4 mx-auto">
                <div class="mt-6 flex items-center">
                    <h1 class="text-xl font-bold text-gray-900 cursor-default">Klassen ({{ classes.length }})</h1>

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
                                                <span>Docent(en)</span>
                                            </th>

                                            <th scope="col"
                                                class="px-4 py-3.5 text-sm font-normal text-center rtl:text-right text-gray-500">
                                                Acties
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="studentClass in paginatedStudentClasses" :key="studentClass.id">
                                            <td class="px-4 py-2 text-sm font-medium whitespace-nowrap">{{ studentClass.name
                                            }}
                                            </td>

                                            <td class="px-4 py-2 text-sm font-medium text-center whitespace-nowrap">{{
                                                studentClass.teachers.map((teacher) => teacher.name).join(', ') }}</td>

                                            <td class="px-4 py-2 text-sm font-medium text-center whitespace-nowrap"><button
                                                    @click="createShowModal(studentClass)"
                                                    class="px-4 py-4 hover:scale-105">
                                                    <EyeIcon class="w-6 h-6" />
                                                </button>
                                                <button @click="createEditModal(studentClass)"
                                                    class="px-4 py-4 hover:scale-105">
                                                    <EditIcon class="w-6 h-6" />
                                                </button>
                                                <button @click="removeClass(studentClass)" class="hover:scale-105">
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
    </div>
</template>

<script setup lang="ts">
import { Ref, computed, defineAsyncComponent, ref, watch } from 'vue';
import SearchInput from 'components/inputs/SearchInput.vue';
import Pagination from 'components/table/Pagination.vue';
import NameFilterIcon from 'components/icons/NameFilterIcon.vue';
import { classStore } from '..';
import { StudentClass } from '../types';
import { confirmModal, formModal, showModal } from 'services/modal';
import { successToast } from 'services/toast';
import { New, Updatable } from 'types/generics';
import TrashIcon from 'components/icons/TrashIcon.vue';
import EditIcon from 'components/icons/EditIcon.vue';
import EyeIcon from 'components/icons/EyeIcon.vue';

classStore.actions.getAll();

const searchAndPage = ref({
    searchQuery: '',
    currentPage: 1,
    perPage: 10,
});

const newStudentClass = ref<New<StudentClass>>({
    name: '',
    info: '',
    day_off: 0,
    teachers: [],
    students: [],
    grade: 0,
});

const filterOnName = ref(false);

const classes = computed(() => classStore.getters.all.value);

const filteredStudentClasses = computed(() => {
    const query = searchAndPage.value.searchQuery.toLowerCase().trim();

    return classes.value
        .filter(student => student.name.toLowerCase().includes(query))
        .sort((a, b) => (filterOnName.value ? a.name.localeCompare(b.name) : 0));
});

const totalPages = computed(() => Math.ceil(filteredStudentClasses.value.length / searchAndPage.value.perPage));

const paginatedStudentClasses = computed(() => {
    const start = (searchAndPage.value.currentPage - 1) * searchAndPage.value.perPage;
    const end = start + searchAndPage.value.perPage;
    return filteredStudentClasses.value.slice(start, end);
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

const createCreateModal = () => {
    formModal(
        newStudentClass.value,
        defineAsyncComponent(async () => import('../components/Form.vue')),
        async (studentClass: New<StudentClass>) => {
            await classStore.actions.create(studentClass);

            formModal(
                new File([], ''),
                defineAsyncComponent(async () => import('../components/UploadStudentsFromFile.vue')),
                async (file: File) => {
                    const classId = await classStore.actions.getLastClass();
                    await classStore.actions.sendStudentsFile(classId.id, file);

                    successToast('Klas met studenten aangemaakt voor BitClock');
                }
            );
        }
    );
};

const createEditModal = (studentClass: StudentClass) => {
    formModal(
        studentClass,
        defineAsyncComponent(async () => import('../components/Form.vue')),
        async (editedStudentClass: Updatable<StudentClass>) => {
            await classStore.actions.update(studentClass.id, editedStudentClass);
            successToast('Klas is aangepast');
        },
    );
};

const createShowModal = (studentClass: StudentClass) => {
    showModal(defineAsyncComponent(() => import('./Show.vue')),
        { studentClass });
}

const removeClass = async (studentClass: StudentClass) => {
    const confirmed = await confirmModal(`Weet je zeker dat je "${studentClass.name}" wilt verwijderen? LET OP: Alle studenten van deze klas worden verwijderd!`, 'Verwijderen');
    if (!confirmed) return;
    await classStore.actions.delete(studentClass.id);
    successToast('Klas & studenten verwijderd');
};

</script>
