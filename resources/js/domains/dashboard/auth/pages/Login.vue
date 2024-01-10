<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="px-8 py-6 mt-4 text-left bg-white shadow-lg">
            <h3 class="text-2xl font-bold text-center">BitClock</h3>
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <div>
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold "
                            for="email">Email</label>
                        <input type="email" placeholder="Email"
                            class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            v-model="credentials.email" />
                    </div>
                    <div class="mt-4">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold">Password</label>
                        <input type="password" placeholder="Password"
                            class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            v-model="credentials.password" />
                    </div>
                    <div class="flex items-baseline justify-between gap-2">
                        <button class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900">
                            Login
                        </button>
                        <router-link :to="{ name: 'forgotPassword' }"
                            class="text-sm text-blue-600 hover:underline">Wachtwoord
                            <br>vergeten?</router-link>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="js">
import { DASHBOARD_DOMAIN_NAME } from 'domains/dashboard/home';
import { goToOverviewPage } from 'services/router';
import { ref } from 'vue';
import { login } from '../index';
import { successToast } from 'services/toast';

const credentials = ref({
    email: '',
    password: ''
});

const submit = async () => {
    await login(credentials.value);
    successToast("Je bent ingelogd!");
    goToOverviewPage(DASHBOARD_DOMAIN_NAME);
};
</script>

<style>
</style>
