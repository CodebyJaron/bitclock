<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="px-16 py-6 mt-4 text-left bg-white shadow-lg">
            <h3 class="text-2xl font-bold text-center">BitClock</h3>
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <div>
                        <label class="block" for="name">Volledige naam</label>
                        <input type="name" placeholder="Naam"
                            class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            v-model="userToRegister.name" disabled />
                    </div>
                    <div class="mt-4">
                        <div>
                            <label class="block" for="email">Email</label>
                            <input type="email" placeholder="Email"
                                class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                                v-model="userToRegister.email" disabled />
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block">Password</label>
                        <input type="password" placeholder="Password"
                            class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            v-model="newCredentials.password" />
                    </div>
                    <div class="mt-4">
                        <label class="block">Herhaal wachtwoord</label>
                        <input type="password" placeholder="Password"
                            class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            v-model="newCredentials.repeatedPassword" />
                    </div>
                    <div class="flex items-baseline justify-between">
                        <button class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900">
                            Login
                        </button>
                        <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { DASHBOARD_DOMAIN_NAME } from 'domains/dashboard/home';
import { User } from 'domains/dashboard/user/types';
import { getRequest, postRequest } from 'services/http';
import { getCurrentRouteToken, goToOverviewPage } from 'services/router';
import { ref } from 'vue';
import { login } from '..';

const newCredentials = ref({ password: '', repeatedPassword: '' });

const userToRegister = ref<User>({
    id: 0,
    name: '',
    email: '',
    invite_token: '',
    is_admin: false,
});

const getUserToRegister = async (token: string) => {
    const { data } = await getRequest(`get-user-to-register/${token}`);
    if (!data) return;
    userToRegister.value = data;
};

const submit = async () => {
    await postRequest(`register/${userToRegister.value.invite_token}`, { invite_token: userToRegister.value.invite_token, password: newCredentials.value.password, repeatedPassword: newCredentials.value.repeatedPassword });
    await login({ email: userToRegister.value.email, password: newCredentials.value.password });
    goToOverviewPage(DASHBOARD_DOMAIN_NAME);
};

getUserToRegister(getCurrentRouteToken());

</script>
