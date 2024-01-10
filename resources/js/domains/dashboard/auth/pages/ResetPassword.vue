<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="px-8 py-6 mt-4 text-left bg-white shadow-lg">
            <h3 class="text-2xl font-bold text-center">BitClock</h3>
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <div>
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold"
                            for="password">Nieuwe
                            wachtwoord</label>
                        <input type="password" placeholder="Wachtwoord"
                            class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            v-model="newPassword.password" />
                    </div>

                    <div class="mt-4">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold" for="password">
                            Wachtwoord herhalen</label>
                        <input type="password" placeholder="Wachtwoord"
                            class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            v-model="newPassword.password_confirmation" />
                    </div>

                    <div class="flex items-baseline justify-between">
                        <button class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900">
                            Aanpassen
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { postRequest, putRequest } from 'services/http';
import { onMounted, reactive, ref } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const newPassword = reactive({
    password: '',
    password_confirmation: '',
    reset_token: route.query.reset_token as string,
});

const submit = async () => {
    await putRequest('/password', newPassword);
};

onMounted(async () => {
    await postRequest('/password/validate-reset-token', {
        reset_token: route.query.reset_token,
    });
});
</script>
