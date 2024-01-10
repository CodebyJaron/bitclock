import {createOverviewRoute} from 'services/router/factory';
import { setTranslation } from 'services/translation';
import OverviewPage from './pages/Overview.vue';

export const SETTINGS_DOMAIN_NAME = 'settings';

setTranslation(SETTINGS_DOMAIN_NAME, {
    singular: 'setting',
    plural: 'settings',
});

export const settingsRoutes = [createOverviewRoute(SETTINGS_DOMAIN_NAME, OverviewPage)];
