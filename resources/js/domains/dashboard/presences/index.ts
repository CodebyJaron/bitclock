import {createOverviewRoute} from 'services/router/factory';
import {setTranslation} from 'services/translation';
import Overview from './pages/Overview.vue';

export const PRESENSES_DOMAIN_NAME = 'aanwezigheid';

setTranslation(PRESENSES_DOMAIN_NAME, {
    plural: 'aanwezigheid',
    singular: 'aanwezigheid',
});

export const presencesRoutes = [createOverviewRoute(PRESENSES_DOMAIN_NAME, Overview)];
