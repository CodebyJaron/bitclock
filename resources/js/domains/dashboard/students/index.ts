import {Student} from './types';
import {createOverviewRoute} from 'services/router/factory';
import {setTranslation} from 'services/translation';
import {storeModuleFactory} from 'services/store';
import OverviewPage from './pages/Overview.vue';
import { postRequest } from 'services/http';

export const STUDENT_DOMAIN_NAME = 'students';

setTranslation(STUDENT_DOMAIN_NAME, {
    singular: 'student',
    plural: 'students',
});

export const baseStudentStore = storeModuleFactory<Student>(STUDENT_DOMAIN_NAME);

export const studentStore = {
    getters: baseStudentStore.getters,
    setters: baseStudentStore.setters,
    actions: {
        ...baseStudentStore.actions,

        addMinutesToStudent: async (student: Student, minutes: number) => {
            await postRequest('/students/' + student.id + '/minutes', {minutes});
        },
    },
};

export const studentRoutes = [createOverviewRoute(STUDENT_DOMAIN_NAME, OverviewPage)];
