<template>
    <div v-if="totalPages > 1" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
        <svg class="h-6 w-6 cursor-pointer" @click="previousPage" :class="{ 'text-gray-400': currentPage === 1 }" width="24"
            height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M9 12C9 12.2652 9.10536 12.5196 9.29289 12.7071L13.2929 16.7072C13.6834 17.0977 14.3166 17.0977 14.7071 16.7072C15.0977 16.3167 15.0977 15.6835 14.7071 15.293L11.4142 12L14.7071 8.70712C15.0977 8.31659 15.0977 7.68343 14.7071 7.29289C14.3166 6.90237 13.6834 6.90237 13.2929 7.29289L9.29289 11.2929C9.10536 11.4804 9 11.7348 9 12Z"
                fill="#18A0FB" />
        </svg>

        <p v-for="page in totalPages" :key="page"
            class="leading-relaxed cursor-pointer mx-2 text-blue-600 hover:text-blue-600 text-sm" @click="goToPage(page)"
            :class="{ 'font-bold': currentPage === page }">
            {{ page }}
        </p>

        <svg class="h-6 w-6 cursor-pointer" @click="nextPage" :class="{ 'text-gray-400': currentPage === totalPages }"
            width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M15 12C15 11.7348 14.8946 11.4804 14.7071 11.2929L10.7071 7.2929C10.3166 6.9024 9.6834 6.9024 9.2929 7.2929C8.9024 7.6834 8.9024 8.3166 9.2929 8.7071L12.5858 12L9.2929 15.2929C8.9024 15.6834 8.9024 16.3166 9.2929 16.7071C9.6834 17.0976 10.3166 17.0976 10.7071 16.7071L14.7071 12.7071C14.8946 12.5196 15 12.2652 15 12Z"
                fill="#18A0FB" />
        </svg>
    </div>
</template>

<script setup>
import { defineProps, ref } from 'vue';

const { currentPage, totalPages, previousPage, nextPage, goToPage } = defineProps([
    'currentPage',
    'totalPages',
    'previousPage',
    'nextPage',
    'goToPage',
]);
</script>
