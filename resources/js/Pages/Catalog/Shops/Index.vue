<script setup>
import EmptyState from '@/Components/EmptyState.vue';
import ProductCard from '@/Components/ProductCard.vue';
import PromoBanner from '@/Components/PromoBanner.vue';
import MarketLayout from '@/Layouts/MarketLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps({
    shops: {
        type: Array,
        default: () => [],
    },
    featuredProducts: {
        type: Array,
        default: () => [],
    },
    promoBanners: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
    searchQuery: {
        type: String,
        default: '',
    },
});

const totalProducts = computed(() => props.shops.reduce(
    (total, shop) => total + Number(shop.active_products_count || 0),
    0,
));

const featuredShops = computed(() => props.shops.filter(
    (shop) => Number(shop.active_products_count || 0) >= 6,
).length);

const normalizedSearchQuery = computed(() => props.searchQuery.trim());

const hasSearchQuery = computed(() => normalizedSearchQuery.value.length > 0);

// Получить первые 3 товара для магазина
const getShopPreviewProducts = (shop) => {
    return props.featuredProducts
        ?.filter(product => product.shop?.id === shop.id)
        .slice(0, 3) || [];
};
</script>

<template>
    <Head title="Каталог магазинов" />

    <MarketLayout>
        <!-- Компактный Hero -->
        <template #header>
            <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-8 shadow-lg">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.2),transparent_40%)]" />

                <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white md:text-4xl">
                            Каталог магазинов
                        </h1>
                        <p
                            v-if="hasSearchQuery"
                            class="mt-2 text-white/90 text-sm md:text-base"
                        >
                            Результаты по запросу «{{ normalizedSearchQuery }}»: {{ shops.length }} витрин • {{ featuredProducts.length }} товаров
                        </p>
                        <p
                            v-else
                            class="mt-2 text-white/90 text-sm md:text-base"
                        >
                            {{ shops.length }} активных витрин • {{ totalProducts }} товаров доступно
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <div class="rounded-xl bg-white/20 backdrop-blur-sm px-4 py-2 text-white">
                            <div class="text-2xl font-bold">{{ shops.length }}</div>
                            <div class="text-xs">магазинов</div>
                        </div>
                        <div class="rounded-xl bg-white/20 backdrop-blur-sm px-4 py-2 text-white">
                            <div class="text-2xl font-bold">{{ featuredShops }}</div>
                            <div class="text-xs">топ витрин</div>
                        </div>
                    </div>
                </div>
            </section>
        </template>

        <!-- Промо-баннеры -->
        <section v-if="promoBanners && promoBanners.length > 0" class="section-container pt-4">
            <PromoBanner :images="promoBanners" />
        </section>

        <!-- Горизонтальная прокрутка категорий -->
        <section v-if="categories && categories.length > 0" class="section-container pt-4">
            <div class="overflow-x-auto scrollbar-hide -mx-4 px-4">
                <div class="flex gap-3 pb-2">
                    <div
                        v-for="category in categories"
                        :key="category.title"
                        class="flex-shrink-0 w-28"
                    >
                        <Link
                            :href="category.link"
                            class="block rounded-xl p-3 text-center transition-all duration-300 hover:shadow-md hover:-translate-y-1"
                            :style="{ backgroundColor: category.color }"
                        >
                            <div class="text-2xl mb-1">{{ category.emoji }}</div>
                            <div class="text-xs font-semibold text-gray-900">{{ category.title }}</div>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <div class="section-container pb-16 pt-6">
            <section
                v-if="hasSearchQuery"
                class="mb-6 rounded-2xl border border-brand-100 bg-brand-50/60 px-5 py-4"
            >
                <h2 class="text-base font-semibold text-slate-900">
                    Поиск: «{{ normalizedSearchQuery }}»
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    Найдено {{ shops.length }} магазинов и {{ featuredProducts.length }} товаров.
                </p>
            </section>

            <EmptyState
                v-if="shops.length === 0 && !hasSearchQuery"
                title="Пока нет активных магазинов"
                description="После публикации магазинов администратором они появятся в каталоге."
            />

            <EmptyState
                v-else-if="shops.length === 0 && featuredProducts.length === 0"
                title="По вашему запросу ничего не найдено"
                description="Попробуйте изменить формулировку запроса, убрать часть слов или воспользоваться более общим названием."
            />

            <!-- Компактные карточки магазинов (3-4 колонки) -->
            <section v-if="shops.length > 0" class="space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">
                        {{ hasSearchQuery ? 'Подходящие магазины' : 'Все магазины' }}
                    </h2>
                    <p class="text-sm text-gray-600">
                        <span v-if="hasSearchQuery">
                            Магазины, в которых найдено совпадение по вашему запросу
                        </span>
                        <span v-else>
                            Выберите магазин и откройте для себя уникальный ассортимент
                        </span>
                    </p>
                </div>

                <section
                    v-if="hasSearchQuery && featuredProducts.length > 0"
                    class="space-y-3"
                >
                    <h3 class="text-lg font-semibold text-slate-900">
                        Найденные товары
                    </h3>
                    <div class="products-grid">
                        <ProductCard
                            v-for="product in featuredProducts"
                            :key="`search-product-${product.id}`"
                            :product="product"
                            show-shop-name
                            compact
                        />
                    </div>
                </section>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <article
                        v-for="shop in shops"
                        :key="shop.id"
                        class="group bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1"
                    >
                        <!-- Заголовок магазина -->
                        <Link
                            :href="route('shops.show', shop.slug)"
                            class="block"
                        >
                            <div class="relative overflow-hidden bg-gradient-to-br from-purple-500 to-pink-500 px-5 py-6">
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.3),transparent_40%)]" />

                                <div class="relative flex items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/20 text-lg font-bold text-white backdrop-blur">
                                        {{ shop.name.slice(0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-bold text-white truncate">
                                            {{ shop.name }}
                                        </h3>
                                        <p class="text-xs text-white/80">
                                            {{ shop.active_products_count }} товаров
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Превью товаров (первые 3 товара) -->
                            <div
                                v-if="getShopPreviewProducts(shop).length > 0"
                                class="p-3 bg-gray-50"
                            >
                                <div class="grid grid-cols-3 gap-2">
                                    <div
                                        v-for="product in getShopPreviewProducts(shop)"
                                        :key="product.id"
                                        class="aspect-square rounded-lg bg-white overflow-hidden"
                                    >
                                        <img
                                            v-if="product.image_url"
                                            :src="product.image_url"
                                            :alt="product.name"
                                            class="w-full h-full object-cover"
                                        />
                                        <div
                                            v-else
                                            class="w-full h-full bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center text-xl font-bold text-purple-600"
                                        >
                                            {{ product.name.slice(0, 1) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Описание -->
                            <div class="p-4 space-y-3">
                                <p class="text-sm text-gray-600 line-clamp-2 min-h-[2.5rem]">
                                    {{ shop.description || 'Широкий ассортимент качественных товаров по доступным ценам' }}
                                </p>

                                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                    <span class="text-sm font-semibold text-gray-900">
                                        Смотреть товары
                                    </span>
                                    <svg
                                        class="w-5 h-5 text-purple-600 transition-transform group-hover:translate-x-1"
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
                                </div>
                            </div>
                        </Link>
                    </article>
                </div>
            </section>
        </div>
    </MarketLayout>
</template>

<style scoped>
/* Скрываем скроллбар для горизонтальной прокрутки категорий */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>
