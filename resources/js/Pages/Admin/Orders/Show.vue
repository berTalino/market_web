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
                    <p class="section-eyebrow border-white/20 bg-white/10 text-white shadow-none">
                        Order detail
                    </p>
                    <h1 class="mt-3 text-3xl font-semibold text-white sm:text-4xl">
                        Заказ #{{ order.id }}
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80">
                        Подробности заказа и состав корзины на момент оформления.
                    </p>
                </div>

                <Link
                    :href="route('admin.orders.index')"
                    class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/15"
                >
                    ← Назад к списку заказов
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <section class="grid gap-4 xl:grid-cols-4">
                <article class="elevated-card xl:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                        Покупатель
                    </p>
                    <p class="mt-3 text-2xl font-semibold text-slate-950">
                        {{ order.customer_name }}
                    </p>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        {{ order.customer_email || 'Email не указан' }} · {{ order.customer_phone }}
                    </p>
                </article>

                <article class="elevated-card">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                        Статус
                    </p>
                    <div class="mt-3">
                        <StatusBadge
                            :value="order.status"
                            :label="formatOrderStatus(order.status)"
                        />
                    </div>
                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Оформлен {{ formatDateTime(order.created_at) }}
                    </p>
                </article>

                <article class="elevated-card">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                        Итого
                    </p>
                    <p class="mt-3 text-2xl font-semibold text-slate-950">
                        {{ formatPrice(order.total_amount) }}
                    </p>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        {{ order.items.length }} товарных позиций в заказе
                    </p>
                </article>
            </section>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.3fr),380px]">
                <section class="surface-card">
                    <div class="flex flex-wrap items-center gap-3">
                        <StatusBadge
                            :value="order.status"
                            :label="formatOrderStatus(order.status)"
                        />
                        <span class="text-sm text-slate-500">
                            Оформлен {{ formatDateTime(order.created_at) }}
                        </span>
                    </div>

                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <p class="section-eyebrow">
                            Customer & session
                        </p>
                    </div>

                    <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Покупатель</dt>
                            <dd class="mt-1 text-base font-semibold text-slate-950">{{ order.customer_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Телефон</dt>
                            <dd class="mt-1 text-base text-slate-900">{{ order.customer_phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Email</dt>
                            <dd class="mt-1 text-base text-slate-900">{{ order.customer_email || 'Не указан' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Session ID</dt>
                            <dd class="mt-1 break-all text-sm text-slate-900">{{ order.session_id }}</dd>
                        </div>
                        <div class="sm:col-span-2 rounded-brand-md border border-slate-100 bg-slate-50/80 p-4">
                            <dt class="text-sm font-medium text-slate-500">Комментарий</dt>
                            <dd class="mt-2 text-base text-slate-900">{{ order.comment || 'Комментарий не оставлен' }}</dd>
                        </div>
                    </dl>
                </section>

                <aside class="space-y-4">
                    <div class="elevated-card brand-gradient-bg shadow-brand">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-white/65">
                            Checkout snapshot
                        </p>
                        <h2 class="mt-3 text-2xl font-semibold text-white">
                            Сводка заказа
                        </h2>
                        <p class="mt-3 text-sm leading-6 text-white/80">
                            Финальные данные заказа сохранены как часть текущего checkout-flow и отображаются в новой визуальной композиции.
                        </p>
                    </div>

                    <div class="surface-card">
                        <h2 class="text-lg font-semibold text-slate-950">
                            Финансовая сводка
                        </h2>
                        <dl class="mt-4 space-y-3 text-sm text-slate-600">
                            <div class="flex items-center justify-between gap-4 rounded-brand-md border border-slate-100 bg-slate-50/70 px-4 py-3">
                                <dt>Позиции</dt>
                                <dd class="font-medium text-slate-950">{{ order.items.length }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 rounded-brand-md border border-slate-100 bg-slate-50/70 px-4 py-3">
                                <dt>Подытог</dt>
                                <dd class="font-medium text-slate-950">{{ formatPrice(order.subtotal_amount) }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 rounded-brand-md border border-brand-100 bg-brand-50 px-4 py-3 text-base">
                                <dt class="font-semibold text-slate-950">Итого</dt>
                                <dd class="font-semibold text-slate-950">{{ formatPrice(order.total_amount) }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="surface-card">
                        <h2 class="text-lg font-semibold text-slate-950">
                            Навигация
                        </h2>
                        <div class="mt-4 space-y-3 text-sm">
                            <Link
                                :href="route('admin.orders.index')"
                                class="btn-secondary w-full"
                            >
                                К списку заказов
                            </Link>
                        </div>
                    </div>
                </aside>
            </div>

            <section class="surface-card overflow-hidden p-0">
                <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="section-eyebrow">
                            Line items
                        </p>
                        <h2 class="mt-3 text-2xl font-semibold text-slate-950">
                            Состав заказа
                        </h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Зафиксированные позиции корзины, цены и ссылки на публичные карточки товаров.
                        </p>
                    </div>

                    <div class="badge-pill">
                        {{ order.items.length }} позиций
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Товар
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Магазин
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Цена
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Кол-во
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Сумма
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr
                                v-for="item in order.items"
                                :key="item.id"
                                class="align-top transition hover:bg-slate-50/70"
                            >
                                <td class="px-6 py-5 text-sm text-slate-600">
                                    <div class="font-medium text-slate-950">
                                        {{ item.product_name }}
                                    </div>
                                    <div
                                        v-if="item.product?.slug && item.shop?.slug"
                                        class="mt-2"
                                    >
                                        <Link
                                            :href="route('products.show', { shop: item.shop.slug, product: item.product.slug })"
                                            class="text-sm font-semibold text-brand-700 transition hover:text-brand-800"
                                        >
                                            Открыть публичную карточку
                                        </Link>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-600">
                                    {{ item.shop?.name ?? '—' }}
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-600">
                                    {{ formatPrice(item.unit_price) }}
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-600">
                                    {{ item.quantity }}
                                </td>
                                <td class="px-6 py-5 text-sm font-semibold text-slate-950">
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
