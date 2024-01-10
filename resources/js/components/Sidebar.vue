<template>
    <aside
        class="fixed z-10 top-0 pb-3 px-6 w-full flex flex-col justify-between h-screen border-r bg-white transition duration-300 md:w-4/12 lg:w-[25%] xl:w-[20%] 2xl:w-[15%]">
        <div>
            <div class="mt-4 mb-4 flex justify-center">
                <div>
                    <img src="@/assets/images/BitClock.png" width="150" alt="logo">
                </div>
            </div>
            <div class="overflow-auto h-[calc(100%-6rem)]">
                <ul class="space-y-2 tracking-wide bg-gray-200 rounded">
                    <div class="text-center">
                        <label class="px-3 text-xs text-gray-500 uppercase dark:text-gray-400">overview</label>
                    </div>

                    <li class="hover:scale-105 transition-transform duration-200">
                        <router-link :to="{ name: 'dashboard.overview' }"
                            class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-700 group hover:bg-gray-300">
                            <svg class="h-6 w-6 text-gray-700 group-hover:text-gray-900" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <polyline points="5 12 3 12 12 3 21 12 19 12" />
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                            </svg>
                            <span class="group-hover:text-gray-900">Home</span>
                        </router-link>
                    </li>
                </ul>

                <ul class="space-y-2 tracking-wide mt-8 bg-gray-200 rounded">
                    <div class="text-center">
                        <label class="px-3 text-xs text-gray-500 uppercase dark:text-gray-400">administratie</label>
                    </div>

                    <li class="hover:scale-105 transition-transform duration-200">
                        <router-link :to="{ name: 'class.overview' }"
                            class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-700 group hover:bg-gray-300">
                            <ClassIcon class="h-6 w-6 text-gray-700 group-hover:text-gray-900" />


                            <span class="group-hover:text-gray-900">Klassen</span>
                        </router-link>
                    </li>
                    <li class="hover:scale-105 transition-transform duration-200">
                        <router-link :to="{ name: 'students.overview' }"
                            class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-700 group hover:bg-gray-300">
                            <StudentIcon class="h-6 w-6 text-gray-700 group-hover:text-gray-900" />
                            <span class="group-hover:text-gray-900">Studenten</span>
                        </router-link>
                    </li>


                    <li class="hover:scale-105 transition-transform duration-200">
                        <router-link :to="{ name: 'aanwezigheid.overview' }"
                            class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-700 group hover:bg-gray-300">
                            <CalendarWithCheckIcon class="h-6 w-6 text-gray-700 group-hover:text-gray-900" />
                            <span class="group-hover:text-gray-900">Aanwezigheid</span>
                        </router-link>
                    </li>

                    <li class="hover:scale-105 transition-transform duration-200">
                        <router-link :to="{ name: 'logs.overview' }"
                            class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-700 group hover:bg-gray-300">
                            <LogIcon class="h-6 w-6 text-gray-700 group-hover:text-gray-900" />
                            <span class="group-hover:text-gray-900">Logs</span>
                        </router-link>
                    </li>
                </ul>

                <ul class="space-y-2 tracking-wide mt-8 bg-gray-200 rounded">
                    <div class="text-center">
                        <label class="px-3 text-xs text-gray-500 uppercase dark:text-gray-400">gebruiker</label>
                    </div>

                    <li class="hover:scale-105 transition-transform duration-200">
                        <router-link :to="{ name: 'settings.overview' }">
                            <a href="#"
                                class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-700 group hover:bg-gray-300">
                                <UserSettingsIcon class="h-6 w-6 text-gray-700 group-hover:text-gray-900" />
                                <span class="group-hover:text-gray-900">Instellingen</span>
                            </a>
                        </router-link>
                    </li>
                </ul>

                <ul v-if="getLoggedInUser?.is_admin" class="space-y-2 tracking-wide mt-8 bg-gray-200 rounded">
                    <div class="text-center">
                        <label class="px-3 text-xs text-gray-500 uppercase dark:text-gray-400">eigenaar</label>
                    </div>

                    <li class="hover:scale-105 transition-transform duration-200">
                        <router-link :to="{ name: 'docenten.overview' }"
                            class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-700 group hover:bg-gray-300">
                            <ClassIcon class="h-6 w-6 text-gray-700 group-hover:text-gray-900" />
                            <span class="group-hover:text-gray-900">Docenten</span>
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
        <div class="absolute w-full bottom-0 left-0 z-50 pl-6 pt-4 pb-4 flex justify-between items-center border-t">
            <button class="hover:scale-105 px-4 py-3 flex items-center space-x-4 rounded-md text-gray-700 group">
                <svg class="h-6 w-6 text-gray-700" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" />
                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                    <path d="M20 12h-13l3 -3m0 6l-3 -3" />
                </svg>
                <span @click="logout" class="group-hover:text-gray-700">Logout</span>
            </button>
        </div>
    </aside>
</template>

<script setup lang="ts">
import StudentIcon from './icons/StudentIcon.vue';
import ClassIcon from './icons/ClassIcon.vue';
import LogIcon from './icons/LogIcon.vue';
import { getLoggedInUser, logout, me } from 'domains/dashboard/auth';
import { onMounted } from 'vue';
import UserSettingsIcon from './icons/UserSettingsIcon.vue';
import CalendarWithCheckIcon from './icons/CalendarWithCheckIcon.vue';

onMounted(async () => await me());
</script>
