export const beautifyDate = (input: string | Date) => {
    if (!input) return '';
    const date = new Date(input);
    const formattedDate = new Intl.DateTimeFormat('nl-NL', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(date);

    return formattedDate;
};

export const getDaysOfWeek = (date: string | null) => {
    const today = date ? new Date(date) : new Date();
    const daysOfWeek = [];

    for (let i = 1; i <= 5; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() + (i - today.getDay()));

        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');

        const formattedDate = `${year}-${month}-${day}`;
        daysOfWeek.push(formattedDate);
    }

    return daysOfWeek;
};
