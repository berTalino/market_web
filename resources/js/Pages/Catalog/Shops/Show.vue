<script setup>
import EmptyState from '@/Components/EmptyState.vue';
import ProductCard from '@/Components/ProductCard.vue';
import MarketLayout from '@/Layouts/MarketLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps({
    shop: {
        type: Object,
        required: true,
    },
    products: {
        type: Array,
        default: () => [],
    },
});

const totalStock = computed(() => props.products.reduce(
    (total, product) => total + Number(product.stock || 0),
    0,
));

const highAvailabilityProducts = computed(() => props.products.filter(
    (product) => Number(product.stock || 0) >= 10,
).length);

// Обработчик добавления в корзину
const handleAddToCart = (product) => {
    router.post(route('cart.items.store'), {
        product_id: product.id,
        quantity: 1,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            // Можно добавить уведомление об успешном добавлении
        },
    });
};
</script>

<template>
    <Head :title="shop.name" />

    <MarketLayout>
        <!-- Компактный Hero с хлебными крошками -->
        <template #header>
            <section class="space-y-4">
                <!-- Хлебные крошки -->
                <nav class="flex items-center gap-2 text-sm">
                    <Link
                        :href="route('welcome')"
                        class="text-gray-600 hover:text-gray-900 transition"
                    >
                        Главная
                    </Link>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <Link
                        :href="route('shops.index')"
                        class="text-gray-600 hover:text-gray-900 transition"
                    >
                        Магазины
                    </Link>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-900 font-medium">{{ shop.name }}</span>
                </nav>

                <!-- Компактный Hero -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-8 shadow-lg">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.2),transparent_40%)]" />

                    <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-white/20 text-2xl font-bold text-white backdrop-blur">
                                    {{ shop.name.slice(0, 1) }}
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold text-white md:text-4xl">
                                        {{ shop.name }}
                                    </h1>
                                    <p class="text-white/90 text-sm mt-1">
                                        {{ products.length }} товаров • {{ totalStock }} в наличии
                                    </p>
                                </div>
                            </div>
                            <p v-if="shop.description" class="text-white/90 text-sm max-w-2xl">
                                {{ shop.description }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <div class="rounded-xl bg-white/20 backdrop-blur-sm px-4 py-2 text-white min-w-[80px]">
                                <div class="text-2xl font-bold">{{ products.length }}</div>
                                <div class="text-xs">товаров</div>
                            </div>
                            <div class="rounded-xl bg-white/20 backdrop-blur-sm px-4 py-2 text-white min-w-[80px]">
                                <div class="text-2xl font-bold">{{ highAvailabilityProducts }}</div>
                                <div class="text-xs">в наличии</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </template>

        <div class="section-container pb-16 pt-6">
            <EmptyState
                v-if="products.length === 0"
                title="В этом магазине пока нет доступных товаров"
                description="Когда у магазина появятся активные товары с остатком, они отобразятся здесь."
            />

            <!-- Сетка товаров с ProductCard (5 колонок на wide экранах) -->
            <section v-else class="space-y-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            Товары магазина
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Все доступные товары с актуальными остатками и ценами
                        </p>
                    </div>

                    <Link
                        :href="route('cart.index')"
                        class="inline-flex items-center justify-center gap-2 rounded-full bg-[#CB11AB] px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-[#A00D8A]"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Корзина
                    </Link>
                </div>

                <!-- Сетка товаров: 2 колонки на mobile, 3 на md, 4 на lg, 5 на xl -->
                <div class="grid grid-cols-2 gap-3 md:grid-cols-3 md:gap-4 lg:grid-cols-4 lg:gap-5 xl:grid-cols-5">
                    <ProductCard
                        v-for="product in products"
                        :key="product.id"
                        :product="{ ...product, shop: shop }"
                        @add-to-cart="handleAddToCart"
                    />
                </div>

                <!-- Информационный блок внизу -->
                <div class="mt-8 p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">
                                Нашли всё, что искали?
                            </h3>
                            <p class="text-sm text-gray-600">
                                Посмотрите другие магазины или вернитесь на главную страницу
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <Link
                                :href="route('shops.index')"
                                class="inline-flex items-center justify-center gap-2 rounded-full border-2 border-purple-600 bg-white px-6 py-2.5 text-sm font-semibold text-purple-600 transition hover:bg-purple-50"
                            >
                                Все магазины
                            </Link>
                            <Link
                                :href="route('welcome')"
                                class="inline-flex items-center justify-center gap-2 rounded-full bg-purple-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-purple-700"
                            >
                                На главную
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </MarketLayout>
</template>
