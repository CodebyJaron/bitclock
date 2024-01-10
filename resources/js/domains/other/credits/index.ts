export const creditRoutes = [
    {
        name: 'credits',
        path: '/credits',
        component: () => import('./pages/Credits.vue'),
        meta: {hideSidebar: true, auth: false, canSeeWhenLoggedIn: true, needCodeAuth: false},
    },
];
