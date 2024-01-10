import {StudentClass} from 'domains/dashboard/classes/types';
import {studentStore} from 'domains/dashboard/students';
import {Student} from 'domains/dashboard/students/types';
import {clockStore} from 'services/clocks';
import {Clock} from 'services/clocks/types';
import {getDaysOfWeek} from './date';

export const clockPresentation = (clock: Readonly<Clock> | undefined) =>
    clock?.outclock_time
        ? `Uitgeklokt om ${clock.outclock_time}`
        : clock?.inclock_time
        ? `Ingeklokt om ${clock?.inclock_time}`
        : 'Afwezig';

export const allClockedInToday = (studentClass: StudentClass) => {
    return studentClass.students.filter(student => {
        const clock = clockStore.getters.findTodayByStudent(student.id);
        return !!clock?.inclock_time;
    });
};

export const allClockedInByClocks = (clocks: Clock[], studentClass: StudentClass) => {
    return studentClass.students.filter(student => {
        const clock = clocks.find(clock => clock.student_id == student.id);
        return !!clock?.inclock_time;
    });
};

export const is_late = (clock: Clock) => {
    return clock.status.toString() === 'te_laat';
};

export const isLateToday = (studentClass?: StudentClass) => {
    if (studentClass) {
        return studentClass.students.filter(student => {
            const clock = clockStore.getters.findTodayByStudent(student.id);
            return clock?.status.toString().includes('te_laat') || false;
        });
    } else {
        return studentStore.getters.all.value.filter(student => {
            const clock = clockStore.getters.findTodayByStudent(student.id);
            return clock?.status.toString().includes('te_laat') || false;
        });
    }
};

export const getToLateTime = (clock: Clock) => {
    const clockinTimeParts = clock.inclock_time.split(':');
    if (clockinTimeParts.length === 3) {
        const currentDateTime = new Date();
        const endTime = new Date(currentDateTime);

        endTime.setHours(9, 0, 0, 0);

        const clockin = new Date(currentDateTime);

        clockin.setHours(Number(clockinTimeParts[0]), Number(clockinTimeParts[1]), Number(clockinTimeParts[2]));

        if (clockin > endTime) {
            const timeDifference = clockin.getTime() - endTime.getTime();
            const minutesLate = Math.floor(timeDifference / (1000 * 60));
            return minutesLate;
        }
    }

    return 0;
};

export const getLast5Clocks = (student: Student) => {
    return clockStore.getters
        .findAllByStudent(student.id)
        .sort((a, b) => {
            return new Date(b.date).getTime() - new Date(a.date).getTime();
        })
        .slice(0, 5);
};
