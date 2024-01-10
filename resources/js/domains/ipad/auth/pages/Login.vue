<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="px-8 py-6 mt-4 text-left bg-white shadow-lg">
            <h3 class="text-2xl font-bold text-center">BitClock</h3>
            <form @submit.prevent="submit">
                <div class="mt-4">

                    <div class="mt-4">
                        <label class="block text-center">Code</label>
                        <input type="password" placeholder="Code"
                            class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            v-model="codeCredentials.code" />
                    </div>
                    <div class="flex items-baseline justify-between">
                        <button class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900">
                            Login
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="js">
import { goToOverviewPage } from 'services/router';
import { ref } from 'vue';
import { codeLogin } from '../index';
import { successToast } from 'services/toast';
import { PUBLIC_HOME_DOMAIN_NAME } from 'domains/ipad/home';

const codeCredentials = ref({
    code: ''
});

const submit = async () => {
    await codeLogin(codeCredentials.value);
    successToast("Code goedgekeurd!");
    goToOverviewPage(PUBLIC_HOME_DOMAIN_NAME);
};
</script>

<style>
</style>
