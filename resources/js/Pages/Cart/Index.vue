<script setup>
import CartItemRow from '@/Components/CartItemRow.vue';
import EmptyState from '@/Components/EmptyState.vue';
import MarketLayout from '@/Layouts/MarketLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { formatPrice } from '@/utils/formatters';

defineProps({
    cart: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Корзина" />

    <MarketLayout>
        <template #header>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900">
                        Корзина
                    </h1>
                    <p class="mt-3 text-base leading-7 text-gray-600">
                        Проверьте состав заказа, обновите количество и переходите к checkout.
                    </p>
                </div>

                <Link
                    :href="route('shops.index')"
                    class="inline-flex items-center text-sm font-medium text-indigo-600 transition hover:text-indigo-700"
                >
                    ← Вернуться в каталог
                </Link>
            </div>
        </template>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <EmptyState
                v-if="cart.is_empty"
                title="Корзина пуста"
                description="Добавьте товары из каталога, чтобы перейти к оформлению заказа."
            >
                <Link
                    :href="route('shops.index')"
                    class="inline-flex items-center rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700"
                >
                    Перейти в каталог
                </Link>
            </EmptyState>

            <div
                v-else
                class="grid gap-6 lg:grid-cols-[1.25fr,0.75fr]"
            >
                <section class="space-y-4">
                    <CartItemRow
                        v-for="item in cart.items"
                        :key="item.product_id"
                        :item="item"
                    />
                </section>

                <aside class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Сводка заказа
                    </h2>

                    <dl class="mt-6 space-y-4 text-sm text-gray-600">
                        <div class="flex items-center justify-between gap-4">
                            <dt>Товаров</dt>
                            <dd class="font-medium text-gray-900">{{ cart.count }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt>Подытог</dt>
                            <dd class="font-medium text-gray-900">{{ formatPrice(cart.subtotal_amount) }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 border-t border-gray-200 pt-4 text-base">
                            <dt class="font-semibold text-gray-900">Итого</dt>
                            <dd class="font-semibold text-gray-900">{{ formatPrice(cart.total_amount) }}</dd>
                        </div>
                    </dl>

                    <Link
                        :href="route('checkout.create')"
                        class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-gray-700"
                    >
                        Перейти к оформлению
                    </Link>
                </aside>
            </div>
        </div>
    </MarketLayout>
</template>
