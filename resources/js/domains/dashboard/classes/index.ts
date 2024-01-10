import {createOverviewRoute} from 'services/router/factory';
import {setTranslation} from 'services/translation';
import {storeModuleFactory} from 'services/store';
import OverviewPage from './pages/Overview.vue';
import {StudentClass} from './types';
import {getRequest, postFileRequest, postRequest} from 'services/http';

export const CLASS_DOMAIN_NAME = 'class';

setTranslation(CLASS_DOMAIN_NAME, {
    singular: 'class',
    plural: 'classes',
});

const baseClassStore = storeModuleFactory<StudentClass>(CLASS_DOMAIN_NAME);

export const classStore = {
    getters: baseClassStore.getters,
    setters: baseClassStore.setters,
    actions: {
        ...baseClassStore.actions,

        sendStudentsFile: async (studentClassId: number, file: File) => {
            await postFileRequest(`/class/${studentClassId}/students`, file);
        },

        getLastClass: async () => {
            const {data} = await getRequest(`/class/get/lastCreated`);

            if (!data) return;
            return data;
        },
    },
};

export const classRoutes = [createOverviewRoute(CLASS_DOMAIN_NAME, OverviewPage)];
