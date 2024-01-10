<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="px-8 py-6 mt-4 text-left bg-white shadow-lg">
            <h3 class="text-2xl font-bold text-center">BitClock</h3>
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <div>
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold"
                            for="email">Email</label>
                        <input type="email" placeholder="Email"
                            class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            v-model="email" />
                    </div>

                    <div class="flex items-baseline justify-between">
                        <button class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900">
                            Verstuur
                        </button>
                        <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { postRequest } from 'services/http';
import { successToast } from 'services/toast';
import { ref } from 'vue';


const email = ref('');

const submit = async () => {
    await postRequest(`/password`, { email: email.value });

    successToast("Als je e-mail is gevonden, krijg je een email!");
}
</script>
