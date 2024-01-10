import {User} from './types';
import {createOverviewRoute} from 'services/router/factory';
import {postRequest} from 'services/http';
import {setTranslation} from 'services/translation';
import {storeModuleFactory} from 'services/store';
import OverviewPage from './pages/Overview.vue';

export const USER_DOMAIN_NAME = 'docenten';

setTranslation(USER_DOMAIN_NAME, {
    singular: 'docenten',
    plural: 'docenten',
});

const baseUserStore = storeModuleFactory<User>(USER_DOMAIN_NAME);

export const userStore = {
    getters: baseUserStore.getters,
    setters: baseUserStore.setters,
    actions: {
        ...baseUserStore.actions,

        reinviteUser: async (user: User) => {
            await postRequest(`${USER_DOMAIN_NAME}/${user.id}/reinvite`, user);
        },
    },
};

export const userRoutes = [createOverviewRoute(USER_DOMAIN_NAME, OverviewPage)];
