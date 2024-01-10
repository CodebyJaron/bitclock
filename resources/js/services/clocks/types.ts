import {Student} from 'domains/dashboard/students/types';

export interface Clock {
    id: number;
    student_id: number;
    name: string;
    date: string;
    inclock_time: string;
    outclock_time: string;
    excercise: string;
    status: Status;
    note: string;
}

export enum Status {
    'aanwezig',
    'afwezig',
    'ziek',
    'te_laat',
    'vrij',
}
