<template>
    <Suspense>
        <div class="flex-1 p-4 min-h-screen ml-[20%] bg-gray-100">
            <form @submit.prevent="submitSettings">
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-2">
                    <div class="-mx-3 md:flex mb-6">
                        <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="name">
                                Naam
                            </label>
                            <input v-model="editedUser.name"
                                class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                id="name" type="text" placeholder="Jaron De Klein" required />
                        </div>
                        <div class="md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                for="email">
                                E-mail
                            </label>
                            <input v-model="editedUser.email"
                                class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                id="email" type="email" placeholder="jarondeklein@ziggo.nl" required />
                        </div>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="submit"
                            class="middle none center rounded-lg bg-blue-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            @click="submitSettings">
                            Opslaan
                        </button>
                    </div>
                </div>
            </form>

            <form v-if="getLoggedInUser?.is_admin && code != null" @submit.prevent="submitCode">
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-2">
                    <div class="-mx-3 md:flex mb-6">
                        <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="name">
                                Public Code
                            </label>
                            <input v-model="editedCode.code"
                                class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                id="name" type="text" placeholder="123456" required />
                        </div>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="submit"
                            class="middle none center rounded-lg bg-blue-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            @click="submitCode">
                            Opslaan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </Suspense>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { getLoggedInUser, loggedInUser } from 'domains/dashboard/auth';
import { User } from 'domains/dashboard/user/types';
import { Updatable } from 'types/generics';
import { userStore } from 'domains/dashboard/user';
import { dangerToast, successToast } from 'services/toast';
import { getCode } from 'domains/ipad/auth/index';
import { postRequest } from 'services/http';

const editedUser = ref<Updatable<User>>({
    name: getLoggedInUser.value?.name || '',
    email: getLoggedInUser.value?.email || '',
    invite_token: null,
    is_admin: false,
});


const code = ref(null);

onMounted(async () => {
    code.value = await getCode();
});

const editedCode = ref({
    code: code.value || '',
    edited_by: !!getLoggedInUser.value?.id || null
});

const submitSettings = async () => {
    if (getLoggedInUser.value?.id == null) {
        dangerToast("Er ging iets fout met het ophalen van jouw gegevens");
        return;
    }

    await userStore.actions.update(getLoggedInUser.value.id, editedUser.value);
    successToast("Je instellingen zijn opgeslage.");
}

const submitCode = async () => {
    const { data } = await postRequest('/code/regenerate', { code: editedCode.value.code, edited_by: editedCode.value.edited_by });
    if (!data) return;

    successToast("De inlog code is gewijzigd!");
    code.value = data.code;
}

</script>
