import {Credentials} from './types';
import {computed, ref} from 'vue';
import {getRequest, getToken, postRequest, setToken} from 'services/http';
import {goToOverviewPage, goToRoute, registerBeforeRouteMiddleware} from 'services/router';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';
import {DASHBOARD_DOMAIN_NAME} from '../home';
import {User} from 'domains/dashboard/user/types';
import RequestResetPassword from './pages/RequestResetPassword.vue';
import ResetPassword from './pages/ResetPassword.vue';

export const authRoutes = [
    {
        path: '/',
        name: 'Login',
        component: Login,
        meta: {auth: false, canSeeWhenLoggedIn: false, hideSidebar: true},
    },
    {
        path: '/wachtwoord-vergeten/reset',
        name: 'resetPassword',
        component: ResetPassword,
        meta: {auth: false, canSeeWhenLoggedIn: false, hideSidebar: true},
    },
    {
        path: '/wachtwoord-vergeten',
        name: 'forgotPassword',
        component: RequestResetPassword,
        meta: {auth: false, canSeeWhenLoggedIn: false, hideSidebar: true},
    },
    {
        path: '/registreren/:token',
        name: 'register',
        component: Register,
        meta: {auth: false, canSeeWhenLoggedIn: false, hideSidebar: true},
    },
];

export const loggedInUser = ref<User | null>(null);

export const isLoggedIn = computed(() => loggedInUser.value !== null);
export const getLoggedInUser = computed(() => loggedInUser.value);

export const login = async (credentials: Credentials) => {
    const {data} = await postRequest('login', credentials);
    if (!data) return;

    setToken(data.token);
    loggedInUser.value = data.user;
};

export const autoLogin = async () => {
    setToken(localStorage.getItem('token'));

    const {data} = await getRequest('/me');
    loggedInUser.value = data.user;
};

export const logout = async () => {
    await getRequest('/logout');

    setToken();

    loggedInUser.value = null;
    goToLoginPage();
};

export const me = async () => {
    const {data} = await getRequest('me');
    if (!data) return;
    loggedInUser.value = data;
};

export const checkIfLoggedIn = async () => {
    if (getToken()) {
        setToken(localStorage.getItem('token'));
    }

    const {data} = await getRequest('me');
    if (!data) return goToLoginPage();
    loggedInUser.value = data.user;
};

export const goToLoginPage = () => goToRoute('Login', undefined);

registerBeforeRouteMiddleware(async ({meta}) => {
    if (!isLoggedIn.value && meta?.auth) {
        goToLoginPage();
        return true;
    }

    if (isLoggedIn.value && !meta?.canSeeWhenLoggedIn) {
        goToOverviewPage(DASHBOARD_DOMAIN_NAME);
        return true;
    }

    return false;
});
