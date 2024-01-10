import { createOverviewRoute } from 'services/router/factory';
import Home from './pages/Home.vue';
import { setTranslation } from 'services/translation';

export const PUBLIC_HOME_DOMAIN_NAME = 'public/home';

setTranslation(PUBLIC_HOME_DOMAIN_NAME, {
    singular: 'public/home',
    plural: 'public/home',
});
export const publicHomeRoutes = [createOverviewRoute(PUBLIC_HOME_DOMAIN_NAME, Home, { canSeeWhenLoggedIn: true, auth: false, hideSidebar: true, needCodeAuth: true, })];
