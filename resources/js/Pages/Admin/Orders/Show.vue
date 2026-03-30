<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { formatDateTime, formatOrderStatus, formatPrice } from '@/utils/formatters';

defineProps({
    order: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head :title="`Админ · Заказ #${order.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">
                        Заказ #{{ order.id }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Подробности заказа и состав корзины на момент оформления.
                    </p>
                </div>

                <Link
                    :href="route('admin.orders.index')"
                    class="inline-flex items-center text-sm font-medium text-indigo-600 transition hover:text-indigo-700"
                >
                    ← Назад к списку заказов
                </Link>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[1.2fr,0.8fr]">
                <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center gap-3">
                        <StatusBadge
                            :value="order.status"
                            :label="formatOrderStatus(order.status)"
                        />
                        <span class="text-sm text-gray-500">
                            Оформлен {{ formatDateTime(order.created_at) }}
                        </span>
                    </div>

                    <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Покупатель</dt>
                            <dd class="mt-1 text-base font-semibold text-gray-900">{{ order.customer_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Телефон</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ order.customer_phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ order.customer_email || 'Не указан' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Session ID</dt>
                            <dd class="mt-1 break-all text-sm text-gray-900">{{ order.session_id }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Комментарий</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ order.comment || 'Комментарий не оставлен' }}</dd>
                        </div>
                    </dl>
                </section>

                <aside class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Сводка
                    </h2>
                    <dl class="mt-4 space-y-3 text-sm text-gray-600">
                        <div class="flex items-center justify-between gap-4">
                            <dt>Позиции</dt>
                            <dd class="font-medium text-gray-900">{{ order.items.length }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt>Подытог</dt>
                            <dd class="font-medium text-gray-900">{{ formatPrice(order.subtotal_amount) }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 border-t border-gray-200 pt-3 text-base">
                            <dt class="font-semibold text-gray-900">Итого</dt>
                            <dd class="font-semibold text-gray-900">{{ formatPrice(order.total_amount) }}</dd>
                        </div>
                    </dl>
                </aside>
            </div>

            <section class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Состав заказа
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                    Товар
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                    Магазин
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                    Цена
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                    Кол-во
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                    Сумма
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr
                                v-for="item in order.items"
                                :key="item.id"
                            >
                                <td class="px-6 py-5 text-sm text-gray-600">
                                    <div class="font-medium text-gray-900">
                                        {{ item.product_name }}
                                    </div>
                                    <div
                                        v-if="item.product?.slug && item.shop?.slug"
                                        class="mt-1"
                                    >
                                        <Link
                                            :href="route('products.show', { shop: item.shop.slug, product: item.product.slug })"
                                            class="text-indigo-600 transition hover:text-indigo-700"
                                        >
                                            Открыть публичную карточку
                                        </Link>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm text-gray-600">
                                    {{ item.shop?.name ?? '—' }}
                                </td>
                                <td class="px-6 py-5 text-sm text-gray-600">
                                    {{ formatPrice(item.unit_price) }}
                                </td>
                                <td class="px-6 py-5 text-sm text-gray-600">
                                    {{ item.quantity }}
                                </td>
                                <td class="px-6 py-5 text-sm font-medium text-gray-900">
                                    {{ formatPrice(item.line_total) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
