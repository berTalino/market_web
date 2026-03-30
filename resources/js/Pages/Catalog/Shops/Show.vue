<script setup>
import EmptyState from '@/Components/EmptyState.vue';
import MarketLayout from '@/Layouts/MarketLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { formatPrice } from '@/utils/formatters';

defineProps({
    shop: {
        type: Object,
        required: true,
    },
    products: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head :title="shop.name" />

    <MarketLayout>
        <template #header>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-sm font-medium uppercase tracking-[0.25em] text-indigo-600">
                        Магазин
                    </p>
                    <h1 class="mt-2 text-3xl font-semibold text-gray-900">
                        {{ shop.name }}
                    </h1>
                    <p class="mt-3 text-base leading-7 text-gray-600">
                        {{ shop.description || 'Описание магазина пока не заполнено.' }}
                    </p>
                </div>

                <Link
                    :href="route('cart.index')"
                    class="inline-flex items-center rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    Открыть корзину
                </Link>
            </div>
        </template>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <Link
                    :href="route('shops.index')"
                    class="text-sm font-medium text-indigo-600 transition hover:text-indigo-700"
                >
                    ← Все магазины
                </Link>
            </div>

            <EmptyState
                v-if="products.length === 0"
                title="В этом магазине пока нет доступных товаров"
                description="Когда у магазина появятся активные товары с остатком, они отобразятся здесь."
            />

            <div
                v-else
                class="grid gap-6 md:grid-cols-2 xl:grid-cols-3"
            >
                <article
                    v-for="product in products"
                    :key="product.id"
                    class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm"
                >
                    <div class="flex items-start justify-between gap-4">
                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ product.name }}
                        </h2>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                            {{ product.stock }} шт.
                        </span>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-600">
                        {{ product.description || 'Описание товара пока отсутствует.' }}
                    </p>
                    <div class="mt-6 flex items-center justify-between gap-4">
                        <div class="text-lg font-semibold text-gray-900">
                            {{ formatPrice(product.price) }}
                        </div>
                        <Link
                            :href="route('products.show', { shop: shop.slug, product: product.slug })"
                            class="inline-flex items-center rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700"
                        >
                            Открыть
                        </Link>
                    </div>
                </article>
            </div>
        </div>
    </MarketLayout>
</template>
