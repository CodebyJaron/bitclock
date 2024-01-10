<template>
    <div v-if="students" class="flex-1 p-4 ml-[20%]">
        <section class="container px-4 mx-auto">

            <div class="mt-6 flex items-center">
                <div class="flex justify-start">
                    <Legenda />
                </div>

                <div class="flex items-center justify-end ml-auto gap-2">
                    <SearchInput :searchAndPage="searchAndPage" />

                    <div class="relative flex items-center mt-4 md:mt-0">
                        <span class="absolute">
                            <CalendarIcon class="w-5 h-5 mx-3 text-gray-400" />
                        </span>
                        <SimpleDatePicker v-model="selectedDate"
                            class="block w-full py-1.5 text-gray-700 bg-white border border-gray-200 rounded-lg w-70 placeholder-gray-400/70 pl-11 rtl:pl-5 focus:outline-none" />
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
                                            class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">
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
                                            class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">
                                            Notitie
                                        </th>

                                        <th scope="col" class="relative py-3.5 px-4">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(student, index) in paginatedStudents" :key="student.id">
                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div>
                                                <h2 class="mb-2 font-medium text-gray-800">{{ student.name }}</h2>
                                                <div class="h-2 flex gap-2">
                                                    <span v-for="day in days" :key="day" :title="beautifyDate(day)" :class="{
                                                        'bg-green-500': clockStore.getters.getByDate(day).find((clock) => clock.student_id == student.id)?.status.toString() === 'aanwezig',
                                                        'bg-red-600': clockStore.getters.getByDate(day).find((clock) => clock.student_id == student.id)?.status.toString() === 'afwezig',
                                                        'bg-amber-400': clockStore.getters.getByDate(day).find((clock) => clock.student_id == student.id)?.status.toString() === 'ziek',
                                                        'bg-rose-400': clockStore.getters.getByDate(day).find((clock) => clock.student_id == student.id)?.status.toString() === 'te_laat',
                                                        'bg-green-900': clockStore.getters.getByDate(day).find((clock) => clock.student_id == student.id)?.status.toString() === 'vrij',
                                                        'bg-indigo-700': !clockStore.getters.getByDate(day).find((clock) => clock.student_id == student.id)?.status
                                                    }" class="inline-block w-2 p-2 bg-opacity-50" />
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div class="relative">
                                                <select @change="changePresence(student.id, selectedDate, $event)"
                                                    :value="clockStore.getters.getByDate(selectedDate).find((clock) => clock.student_id == student.id)?.status"
                                                    class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-3 px-4 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                                    <option value="">Onbekend</option>
                                                    <option value="ziek">Ziek</option>
                                                    <option value="te_laat">Te laat</option>
                                                    <option value="aanwezig">Aanwezig</option>
                                                    <option value="afwezig">Afwezig</option>
                                                    <option value="vrij">Vakantie/vrij</option>
                                                </select>
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M5.516 7.548c0.436-0.446 1.043-0.481 1.576 0l3.908 3.747 3.908-3.747c0.533-0.481 1.141-0.446 1.576 0 0.436 0.445 0.408 1.197 0 1.615-0.406 0.418-4.695 4.502-4.695 4.502-0.217 0.223-0.502 0.335-0.789 0.335s-0.571-0.112-0.789-0.335c0 0-4.287-4.084-4.695-4.502-0.408-0.418-0.436-1.17 0-1.615z" />
                                                    </svg>
                                                </div>
                                            </div>

                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <textarea
                                                @change="changeNote(clockStore.getters.getByDate(selectedDate).find((clock) => clock.student_id == student.id), $event)"
                                                :value="clockStore.getters.getByDate(selectedDate).find((clock) => clock.student_id == student.id)?.note"
                                                class="form-textarea mt-1 block w-full border rounded-lg border-gray-300 focus:outline-none"
                                                rows="2"></textarea>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <button @click="dropdownOptions = (dropdownOptions === index) ? -1 : index"
                                                class="px-1 py-1 text-gray-500 transition-colors duration-200 rounded-lg hover:bg-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010-1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010-1.5z" />
                                                </svg>
                                            </button>

                                            <div id="dropdown-menu" v-if="dropdownOptions === index"
                                                class="origin-top-right absolute right-2 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                                <div class="py-2 p-2" role="menu" aria-orientation="vertical"
                                                    aria-labelledby="dropdown-button">
                                                    <a class="flex block rounded-md px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer"
                                                        role="menuitem"
                                                        @click="changeClockHours(clockStore.getters.getByDate(selectedDate).find((clock) => clock.student_id == student.id))">
                                                        Uren aanpassen
                                                    </a>
                                                    <a class="flex block rounded-md px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer"
                                                        role="menuitem" @click="createRemainingMinutesModal(student)">
                                                        Minuten inhalen
                                                    </a>
                                                </div>
                                            </div>
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
import SimpleDatePicker from 'components/SimpleDatePicker.vue';
import CalendarIcon from 'components/icons/CalendarIcon.vue';
import { studentStore } from 'domains/dashboard/students';
import { Student } from 'domains/dashboard/students/types';
import { beautifyDate, getDaysOfWeek } from 'helpers/date';
import { getCurrentDate } from 'helpers/time';
import { clockStore } from 'services/clocks';
import { Clock } from 'services/clocks/types';
import { postRequest } from 'services/http';
import { formModal, showModal } from 'services/modal';
import { dangerToast, successToast } from 'services/toast';
import { Updatable } from 'types/generics';
import { Ref, computed, defineAsyncComponent, ref, watch } from 'vue';
import Legenda from '../components/Legenda.vue';
import SearchInput from 'components/inputs/SearchInput.vue';
import Pagination from 'components/table/Pagination.vue';
import NameFilterIcon from 'components/icons/NameFilterIcon.vue';
import OtherFilterIcon from 'components/icons/OtherFilterIcon.vue';

studentStore.actions.getAll();
clockStore.actions.getAll();

const selectedDate = ref(getCurrentDate());
const dropdownOptions = ref();
const days = ref(getDaysOfWeek(selectedDate.value));

const searchAndPage = ref({
    searchQuery: '',
    currentPage: 1,
    perPage: 10,
});

const selectedOptions = ref<string[]>([]);
const filterOnName = ref(false);
const dropdownVisible = ref(false);

const students = computed(() => studentStore.getters.all.value.filter(student => student.active));

const filteredStudents = computed(() => {
    const query = searchAndPage.value.searchQuery.toLowerCase();

    let filteredList = students.value.filter(student => {
        const nameIncludesQuery = student.name.toLowerCase().startsWith(query);
        const clocks = clockStore.getters.getByDate(selectedDate.value);
        const matchingClock = clocks.find((clock) => clock.student_id == student.id);

        if (matchingClock) {
            const hasSelectedClockStatus = selectedOptions.value.includes(matchingClock.status.toString());
            return nameIncludesQuery && (!hasSelectedClockStatus);
        } else {
            return nameIncludesQuery && !selectedOptions.value.includes('onbekend');
        }
    });

    if (filterOnName.value) {
        filteredList = filteredList.sort((a, b) => a.name.localeCompare(b.name));
    }

    return filteredList;
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

const createRemainingMinutesModal = (student: Student) => {
    formModal(student, defineAsyncComponent(() => import('../components/remainingMinutesModal.vue')), async (editedStudent: Updatable<Student>) => {
        await studentStore.actions.update(student.id, editedStudent);
        successToast('De inhalende minuten zijn opgeslagen');
    });
}

const changeClockHours = async (clock: Clock | undefined) => {
    if (!clock) {
        return dangerToast('Zet de student eerst op een andere aanwezigheid.');
    }

    formModal(clock, defineAsyncComponent(() => import('../components/changeHoursModal.vue')), async (editedClock: Updatable<Clock>) => {
        await clockStore.actions.update(clock.id, editedClock);
        successToast('De uren zijn opgeslagen');
    });
}

const changePresence = async (studentId: number, date: string, newPresence: Event) => {
    const { data } = await postRequest('clocks/change-presence', {
        student_id: studentId,
        date: date,
        new_presence: (newPresence as any).target?.value,
    });

    if (!data) return;
    clockStore.setters.setById(data);

    successToast('Aanwezigheid is aangepast.');
};

const changeNote = async (clock: Clock | undefined, event: any) => {
    if (!clock) {
        return dangerToast('Zet de student eerst op een andere aanwezigheid.');
    }

    const updatedClock: Clock = Object.assign({}, clock, { note: event.target?.value });
    await clockStore.actions.update(clock.id, updatedClock);

    successToast('Notitie is aangepast.');
}

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

watch(selectedDate, (newDate) => {
    days.value = getDaysOfWeek(newDate);
});

const filterOptions = ref([
    {
        label: 'Onbekend',
        value: 'onbekend',
    },
    {
        label: 'Aanwezig',
        value: 'aanwezig',
    },
    {
        label: 'Afwezig',
        value: 'afwezig',
    },
    {
        label: 'Ziek',
        value: 'ziek',
    },
    {
        label: 'Te laat',
        value: 'te_laat',
    }
]);

</script>
