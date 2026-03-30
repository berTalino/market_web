export const formatPrice = (value) => new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
    maximumFractionDigits: 0,
}).format(Number(value ?? 0));

export const formatDateTime = (value) => {
    if (!value) {
        return '—';
    }

    return new Intl.DateTimeFormat('ru-RU', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
};

export const formatOrderStatus = (value) => {
    const labels = {
        pending: 'Ожидает обработки',
        confirmed: 'Подтверждён',
        completed: 'Выполнен',
        cancelled: 'Отменён',
    };

    return labels[value] ?? value;
};

export const formatRole = (value) => {
    const labels = {
        admin: 'Администратор',
        owner: 'Владелец',
    };

    return labels[value] ?? 'Пользователь';
};
