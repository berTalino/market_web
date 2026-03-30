<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmptyState from '@/Components/EmptyState.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { formatDateTime, formatOrderStatus, formatPrice } from '@/utils/formatters';

defineProps({
    orders: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Админ · Заказы" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Заказы
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Список заказов, оформленных пользователями через checkout.
                </p>
            </div>
        </template>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <EmptyState
                v-if="orders.length === 0"
                title="Заказов пока нет"
                description="Когда клиенты начнут оформлять покупки, здесь появится история заказов."
            />

            <div
                v-else
                class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm"
            >
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                    Заказ
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                    Покупатель
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                    Статус
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                    Позиции / сумма
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                    Оформлен
                                </th>
                                <th class="px-6 py-4" />
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr
                                v-for="order in orders"
                                :key="order.id"
                            >
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-gray-900">
                                        Заказ #{{ order.id }}
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        Session: {{ order.session_id }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm text-gray-600">
                                    <div class="font-medium text-gray-900">
                                        {{ order.customer_name }}
                                    </div>
                                    <div class="mt-1">{{ order.customer_phone }}</div>
                                    <div v-if="order.customer_email" class="mt-1 text-gray-500">
                                        {{ order.customer_email }}
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <StatusBadge
                                        :value="order.status"
                                        :label="formatOrderStatus(order.status)"
                                    />
                                </td>
                                <td class="px-6 py-5 text-sm text-gray-600">
                                    <div>{{ order.items_count }} поз.</div>
                                    <div class="mt-1 font-medium text-gray-900">
                                        {{ formatPrice(order.total_amount) }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm text-gray-600">
                                    {{ formatDateTime(order.created_at) }}
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <Link
                                        :href="route('admin.orders.show', order.id)"
                                        class="text-sm font-medium text-indigo-600 transition hover:text-indigo-700"
                                    >
                                        Открыть
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
