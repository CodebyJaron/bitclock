import { createOverviewRoute } from "services/router/factory";
import { setTranslation } from "services/translation";
import { defineAsyncComponent } from "vue";

export const DISPLAY_OVERVIEW_DOMAIN_NAME = 'overview/aanwezig';

setTranslation(DISPLAY_OVERVIEW_DOMAIN_NAME, {
    singular: 'overview/aanwezig',
    plural: 'overview/aanwezig',
})


export const displayRoute = [createOverviewRoute(DISPLAY_OVERVIEW_DOMAIN_NAME, defineAsyncComponent(() => import('./pages/Overview.vue')), { hideSidebar: true, auth: false, canSeeWhenLoggedIn: true, needCodeAuth: false })];
