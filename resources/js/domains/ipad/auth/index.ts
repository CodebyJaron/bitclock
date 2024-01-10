import {getCodeFromLocalStorage, getRequest, postRequest, setPublicCode} from 'services/http';
import {ref} from 'vue';
import Login from './pages/Login.vue';
import {goToOverviewPage, goToRoute, registerBeforeRouteMiddleware} from 'services/router';
import {PUBLIC_HOME_DOMAIN_NAME} from '../home';

export const publicAuthRoutes = [
    {
        path: '/code/verify',
        name: 'codeauth',
        component: Login,
        meta: {auth: false, canSeeWhenLoggedIn: true, hideSidebar: true},
    },
];

export const isCodeLoggedIn = ref({isCodeLoggedIn: false});

export const codeLogin = async (credentials: {code: string}) => {
    const {data} = await postRequest('/code/login', {code: credentials.code});
    if (!data) return;

    setPublicCode(credentials.code);
    isCodeLoggedIn.value.isCodeLoggedIn = true;
};

export const getCode = async () => {
    const {data} = await getRequest('/code/get');
    if (data && data.hasOwnProperty('code')) {
        return data.code;
    }

    return '';
};

registerBeforeRouteMiddleware(async ({meta}) => {
    if (meta.needCodeAuth && !isCodeLoggedIn.value.isCodeLoggedIn) {
        const code = getCodeFromLocalStorage();
        if (code) {
            try {
                await codeLogin({code});
                goToOverviewPage(PUBLIC_HOME_DOMAIN_NAME);
            } catch (e) {
                goToRoute('codeauth');
            }
            return true;
        }

        return goToRoute('codeauth');
    }
    return false;
});
