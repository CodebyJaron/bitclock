import {storeModuleFactory} from 'services/store';
import {setTranslation} from 'services/translation';
import {Clock} from './types';
import {postRequest} from 'services/http';
import {dangerToast} from 'services/toast';
import {getCurrentDate, getCurrentTime} from 'helpers/time';
import {is_late} from 'helpers/clockHelpers';

export const CLOCKS_DOMAIN_NAME = 'clocks';

setTranslation(CLOCKS_DOMAIN_NAME, {
    singular: 'clocks',
    plural: 'clocks',
});

const baseClockStore = storeModuleFactory<Clock>(CLOCKS_DOMAIN_NAME);

export const clockStore = {
    getters: {
        ...baseClockStore.getters,

        findTodayByStudent: (studentId: number | null) => {
            return clockStore.getters.all.value
                .filter(clock => clock.date == getCurrentDate())
                .find(clock => clock.student_id == studentId);
        },

        findAllByStudent: (studentId: number | null) => {
            return clockStore.getters.all.value.filter(clock => clock.student_id == studentId);
        },

        getByDate: (date: string) => {
            return clockStore.getters.all.value.filter(clock => clock.date == date);
        },
    },
    setters: baseClockStore.setters,
    actions: {
        ...baseClockStore.actions,

        clockIn: async (student_id: number, excercise: string) => {
            const {data} = await postRequest(`/clock/in`, {
                student_id: student_id,
                excercise: excercise,
            });
            if (!data) return;

            return data;
        },

        clockOut: async (student_id: number) => {
            await postRequest(`/clock/out`, {
                student_id: student_id,
            });
        },

        changeStatus: async (clock: Clock, status: string) => {
            const {data} = await postRequest(`/clock/status`, {
                clock_id: clock.id,
                status: status,
            });
            if (!data) return;

            return data;
        },
    },
};
