import { Clock } from "services/clocks/types";

export const getCurrentTime = () => {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    return `${hours}:${minutes}:${seconds}`;
}

export const getCurrentDate = () => {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

export const formatMinutes = (totaalMinuten: number) => {
    const uren = Math.floor(totaalMinuten / 60);
    const minuten = totaalMinuten % 60;

    if (uren === 0) {
      return `${minuten} minuten`;
    } else if (minuten === 0) {
      return `${uren} ${uren > 1 ? 'uur' : 'uur'}`;
    } else {
      return `${uren} ${uren > 1 ? 'uur' : 'uur'} en ${minuten} minuten`;
    }
};

export const getTimeCheckedIn = (clock: Clock) => {
    const nowTime = new Date();
    const startTime = new Date(clock.inclock_time);

    const timeDifference = nowTime.getTime() - startTime.getTime();
    const minutesCheckedIn = Math.floor(timeDifference / (1000 * 60));

    return minutesCheckedIn;
}

export const canCheckout = (clock: Clock) => {
    const nowTime = new Date();
    const startTime = new Date(clock.inclock_time);

    const checkoutTime = new Date(startTime);
    checkoutTime.setHours(16, 30, 0, 0);

    const maxCheckoutTime = new Date(startTime);
    maxCheckoutTime.setMinutes(startTime.getMinutes() + 390);

    return nowTime >= checkoutTime || nowTime >= maxCheckoutTime;
}

