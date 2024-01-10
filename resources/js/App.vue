<template>
    <div class="min-v-full">
        <Sidebar v-if="!hideSidebar" v-cloak />
        <router-view />

    </div>
</template>

<script setup lang="ts">
import Sidebar from 'components/Sidebar.vue';
import { getLoggedInUser, isLoggedIn } from 'domains/dashboard/auth';
import { computed } from 'vue';

import { useRouter } from 'vue-router';

const router = useRouter();
const routeMeta = computed(() => router.currentRoute.value.meta);

const hideSidebar = computed(() => (routeMeta.value.hideSidebar === undefined ? false : routeMeta.value.hideSidebar) || !isLoggedIn.value);
</script>
