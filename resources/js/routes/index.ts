import {userRoutes} from 'domains/dashboard/user';
import {authRoutes} from '../domains/dashboard/auth';
import {studentRoutes} from 'domains/dashboard/students';
import {classRoutes} from 'domains/dashboard/classes';
import {homeRoutes} from 'domains/dashboard/home';
import {publicHomeRoutes} from 'domains/ipad/home';
import {publicAuthRoutes} from 'domains/ipad/auth';
import {settingsRoutes} from 'domains/dashboard/settings';
import {logsRoutes} from 'domains/dashboard/logs';
import {displayRoute} from 'domains/other/overview-display';
import {presencesRoutes} from 'domains/dashboard/presences';
import {creditRoutes} from 'domains/other/credits';

export const routes = [
    ...authRoutes,
    ...homeRoutes,
    ...userRoutes,
    ...studentRoutes,
    ...classRoutes,
    ...publicHomeRoutes,
    ...publicAuthRoutes,
    ...settingsRoutes,
    ...logsRoutes,
    ...displayRoute,
    ...presencesRoutes,
    ...creditRoutes,
];
