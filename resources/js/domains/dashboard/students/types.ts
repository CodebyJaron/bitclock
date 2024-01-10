import {StudentClass} from 'domains/dashboard/classes/types';

export interface Student {
    id: number;
    name: string;
    email: string;
    info: string;
    active: boolean;
    student_class_id: number;
    minutes_open: number;
    created_at: number;
}
