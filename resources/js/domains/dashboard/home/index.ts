import {createOverviewRoute} from 'services/router/factory';
import {setTranslation} from 'services/translation';
import Dashboard from './pages/Dashboard.vue';

export const DASHBOARD_DOMAIN_NAME = 'dashboard';

setTranslation(DASHBOARD_DOMAIN_NAME, {
    singular: 'dashboard',
    plural: 'dashboard',
});

export const homeRoutes = [createOverviewRoute(DASHBOARD_DOMAIN_NAME, Dashboard)];
