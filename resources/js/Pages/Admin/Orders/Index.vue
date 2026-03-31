<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmptyState from '@/Components/EmptyState.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import { formatDateTime, formatOrderStatus, formatPrice } from '@/utils/formatters';

const props = defineProps({
    orders: {
        type: Array,
        default: () => [],
    },
});

const stats = computed(() => {
    const revenue = props.orders.reduce((total, order) => total + Number(order.total_amount ?? 0), 0);

    return [
        {
            label: 'Всего заказов',
            value: props.orders.length,
            note: 'История checkout-сценариев',
        },
        {
            label: 'Ожидают обработки',
            value: props.orders.filter((order) => order.status === 'pending').length,
            note: 'Нуждаются в операционном внимании',
        },
        {
            label: 'Подтверждены',
            value: props.orders.filter((order) => order.status === 'confirmed').length,
            note: 'Готовы к следующему этапу',
        },
        {
            label: 'Оборот',
            value: formatPrice(revenue),
            note: 'Сумма всех оформленных заказов',
        },
    ];
});
</script>

<template>
    <Head title="Админ · Заказы" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="section-eyebrow border-white/20 bg-white/10 text-white shadow-none">
                        Orders control
                    </p>
                    <h1 class="mt-3 text-3xl font-semibold text-white sm:text-4xl">
                        Заказы
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80">
                        Список заказов, оформленных пользователями через checkout, в новой табличной операционной поверхности.
                    </p>
                </div>

                <div class="rounded-brand-lg border border-white/15 bg-white/10 px-4 py-3 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.22em] text-white/60">
                        Всего записей
                    </p>
                    <p class="mt-2 text-xl font-semibold text-white">
                        {{ orders.length }}
                    </p>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <section class="grid gap-4 xl:grid-cols-4">
                <article
                    v-for="item in stats"
                    :key="item.label"
                    class="elevated-card"
                >
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                        {{ item.label }}
                    </p>
                    <p class="mt-3 text-2xl font-semibold text-slate-950">
                        {{ item.value }}
                    </p>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        {{ item.note }}
                    </p>
                </article>
            </section>

            <EmptyState
                v-if="orders.length === 0"
                title="Заказов пока нет"
                description="Когда клиенты начнут оформлять покупки, здесь появится история заказов."
            />

            <section
                v-else
                class="surface-card overflow-hidden p-0"
            >
                <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="section-eyebrow">
                            Table surface
                        </p>
                        <h2 class="mt-3 text-2xl font-semibold text-slate-950">
                            Операционный реестр заказов
                        </h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Контроль статусов, покупателей и состава checkout без изменения существующей логики данных.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <span class="badge-pill">{{ orders.length }} заказов</span>
                        <span class="badge-pill bg-slate-50 text-slate-600">Обновление по Inertia</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Заказ
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Покупатель
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Статус
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Позиции / сумма
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Оформлен
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Действие
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr
                                v-for="order in orders"
                                :key="order.id"
                                class="align-top transition hover:bg-slate-50/70"
                            >
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-slate-950">
                                        Заказ #{{ order.id }}
                                    </div>
                                    <div class="mt-2 text-sm text-slate-500">
                                        Session: {{ order.session_id }}
                                    </div>
                                </td>

                                <td class="px-6 py-5 text-sm text-slate-600">
                                    <div class="font-medium text-slate-900">
                                        {{ order.customer_name }}
                                    </div>
                                    <div class="mt-2">{{ order.customer_phone }}</div>
                                    <div v-if="order.customer_email" class="mt-1 text-slate-500">
                                        {{ order.customer_email }}
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <StatusBadge
                                        :value="order.status"
                                        :label="formatOrderStatus(order.status)"
                                    />
                                </td>

                                <td class="px-6 py-5 text-sm text-slate-600">
                                    <div>{{ order.items_count }} поз.</div>
                                    <div class="mt-2 font-semibold text-slate-950">
                                        {{ formatPrice(order.total_amount) }}
                                    </div>
                                </td>

                                <td class="px-6 py-5 text-sm text-slate-600">
                                    {{ formatDateTime(order.created_at) }}
                                </td>

                                <td class="px-6 py-5 text-right">
                                    <Link
                                        :href="route('admin.orders.show', order.id)"
                                        class="inline-flex items-center rounded-full border border-brand-100 bg-brand-50 px-4 py-2 text-sm font-semibold text-brand-700 transition hover:border-brand-200 hover:bg-brand-100"
                                    >
                                        Открыть
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
