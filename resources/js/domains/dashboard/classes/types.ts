import {Student} from 'domains/dashboard/students/types';
import {User} from 'domains/dashboard/user/types';

export interface StudentClass {
    id: number;
    name: string;
    info: string;
    day_off: number;
    teachers: User[];
    students: Student[];
    grade: number;
}
