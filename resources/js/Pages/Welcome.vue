<script setup>
import MarketLayout from '@/Layouts/MarketLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import PromoBanner from '@/Components/PromoBanner.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps({
    featuredProducts: {
        type: Array,
        default: () => [],
    },
    promoBanners: {
        type: Array,
        default: () => [],
    },
    shops: {
        type: Array,
        default: () => [],
    },
});

// Количество отображаемых товаров
const displayCount = ref(20);

// Показать больше товаров
const loadMore = () => {
    displayCount.value = Math.min(displayCount.value + 10, props.featuredProducts.length);
};

// Обработчик добавления в корзину
const handleAddToCart = (product) => {
    router.post(route('cart.add'), {
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
    <Head title="Главная — Platio Market" />

    <MarketLayout>
        <!-- Промо-баннеры -->
        <section v-if="promoBanners.length > 0" class="section-container pt-4">
            <PromoBanner :images="promoBanners" />
        </section>

        <!-- Популярные товары -->
        <section class="section-container pt-6 pb-8">
            <!-- Заголовок секции -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    Популярные товары
                </h2>
                <p class="text-sm text-gray-600">
                    Лучшие предложения наших магазинов
                </p>
            </div>

            <!-- Сетка товаров -->
            <div
                v-if="featuredProducts.length > 0"
                class="grid grid-cols-2 gap-3 md:grid-cols-3 md:gap-4 lg:grid-cols-4 lg:gap-5 xl:grid-cols-5"
            >
                <ProductCard
                    v-for="product in featuredProducts.slice(0, displayCount)"
                    :key="product.id"
                    :product="product"
                    :show-shop-name="true"
                    @add-to-cart="handleAddToCart"
                />
            </div>

            <!-- Пустое состояние -->
            <div
                v-else
                class="text-center py-16"
            >
                <div class="text-4xl mb-4">📦</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                    Товары скоро появятся
                </h3>
                <p class="text-gray-600 mb-6">
                    Сейчас мы работаем над наполнением каталога
                </p>
                <Link
                    :href="route('shops.index')"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-[#CB11AB] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#A00D8A]"
                >
                    Посмотреть магазины
                </Link>
            </div>

            <!-- Кнопка "Показать ещё товары" -->
            <div
                v-if="featuredProducts.length > displayCount"
                class="text-center mt-8"
            >
                <button
                    @click="loadMore"
                    class="inline-flex items-center justify-center gap-2 rounded-full border-2 border-gray-300 bg-white px-8 py-3 text-sm font-semibold text-gray-700 transition hover:border-gray-400 hover:bg-gray-50"
                >
                    Показать ещё товары
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </button>
            </div>
        </section>

        <!-- Баннер призыв к действию -->
        <section class="section-container pb-8">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-12 text-center text-white shadow-xl">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.2),transparent_50%)]" />
                
                <div class="relative z-10 max-w-2xl mx-auto">
                    <h2 class="text-3xl font-bold mb-4">
                        Откройте для себя новые магазины
                    </h2>
                    <p class="text-lg text-white/90 mb-6">
                        Удобная витрина с лучшими товарами от проверенных продавцов
                    </p>
                    <Link
                        :href="route('shops.index')"
                        class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-8 py-3.5 text-sm font-semibold text-purple-600 shadow-lg transition hover:bg-gray-50"
                    >
                        Посмотреть все магазины
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </Link>
                </div>
            </div>
        </section>
    </MarketLayout>
</template>

