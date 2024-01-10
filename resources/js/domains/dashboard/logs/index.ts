import { createOverviewRoute } from "services/router/factory";
import { setTranslation } from "services/translation";
import Overview from './pages/Overview.vue'

export const LOGS_DOMAIN_NAME = 'logs';

setTranslation(LOGS_DOMAIN_NAME, {
    singular: 'logs',
    plural: 'logs',
});

export const logsRoutes = [createOverviewRoute(LOGS_DOMAIN_NAME, Overview)];
