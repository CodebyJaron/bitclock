import {addRoutes, useRouterInApp} from './services/router';
import {checkIfLoggedIn} from 'domains/dashboard/auth';
import {createApp} from 'vue';
import App from './App.vue';
import '../css/app.css';
import {routes} from './routes';

import {OhVueIcon, addIcons} from 'oh-vue-icons';
import {FaFlag, RiZhihuFill} from 'oh-vue-icons/icons';
import * as FaIcons from 'oh-vue-icons/icons/fa';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';

const Fa = Object.values({...FaIcons});

addIcons(...Fa);
addIcons(FaFlag, RiZhihuFill);

const app = createApp(App);
addRoutes(routes);

try {
    await checkIfLoggedIn();
} catch (_) {
} finally {
    useRouterInApp(app);
    app.component('v-icon', OhVueIcon);
    app.component('multiselect', Multiselect);
    app.mount('#app');
}
