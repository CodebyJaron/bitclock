<template>
    <div class="relative inline-block text-left">
        <div>
            <button type="button"
                class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                id="menu-button" aria-expanded="true" aria-haspopup="true" @click="dropdownOpen = !dropdownOpen">
                Legenda
                <svg class="-mr-1 pt-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <div v-if="dropdownOpen"
            class="absolute right-0 mt-2 z-10 p-2 w-56 origin-top-right divide-y divide-gray-100 border border-gray-200 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="py-1" role="none">
                <div
                    class="text-gray-700 group hover:bg-gray-100 rounded-lg cursor-default flex gap-2 items-center px-4 py-2 text-sm">
                    <span class="inline-block w-2 h-2 p-2 bg-opacity-50 bg-green-500"></span>
                    Aanwezig
                </div>
                <div
                    class="text-gray-700 group hover:bg-gray-100 rounded-lg cursor-default flex gap-2 items-center px-4 py-2 text-sm">
                    <span class="inline-block w-2 h-2 p-2 bg-opacity-50 bg-red-600"></span>
                    Afwezig
                </div>
                <div
                    class="text-gray-700 group hover:bg-gray-100 rounded-lg cursor-default flex gap-2 items-center px-4 py-2 text-sm">
                    <span class="inline-block w-2 h-2 p-2 bg-opacity-50 bg-amber-400"></span>
                    Ziek
                </div>
                <div
                    class="text-gray-700 group hover:bg-gray-100 rounded-lg cursor-default flex gap-2 items-center px-4 py-2 text-sm">
                    <span class="inline-block p-2 w-2 h-2 bg-opacity-50 bg-rose-400"></span>
                    Te laat
                </div>
                <div
                    class="text-gray-700 group hover:bg-gray-100 rounded-lg cursor-default flex gap-2 items-center px-4 py-2 text-sm">
                    <span class="inline-block p-2 w-2 h-2 bg-opacity-50 bg-green-900"></span>
                    Vakantie/vrij
                </div>
                <div
                    class="text-gray-700 group hover:bg-gray-100 rounded-lg cursor-default flex gap-2 items-center px-4 py-2 text-sm">
                    <span class="inline-block w-1.5 h-1.5 p-2 bg-opacity-50 bg-indigo-700"></span>
                    Onbekend
                </div>
            </div>

            <div class="py-2 inline-block cursor-default">
                <p class="text-xs font-semibold"><span class="text-xs font-bold">Let op:</span>
                    Met een 'onbekend' status kan je niks veranderen!</p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const dropdownOpen = ref(false);
</script>
