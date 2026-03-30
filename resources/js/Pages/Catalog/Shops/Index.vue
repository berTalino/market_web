<script setup>
import EmptyState from '@/Components/EmptyState.vue';
import MarketLayout from '@/Layouts/MarketLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps({
    shops: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Каталог магазинов" />

    <MarketLayout>
        <template #header>
            <div class="max-w-3xl">
                <h1 class="text-3xl font-semibold text-gray-900">
                    Каталог магазинов
                </h1>
                <p class="mt-3 text-base leading-7 text-gray-600">
                    Выберите магазин, чтобы просмотреть доступные товары и перейти к оформлению заказа.
                </p>
            </div>
        </template>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <EmptyState
                v-if="shops.length === 0"
                title="Пока нет активных магазинов"
                description="После публикации магазинов администратором они появятся в каталоге."
            />

            <div
                v-else
                class="grid gap-6 md:grid-cols-2 xl:grid-cols-3"
            >
                <Link
                    v-for="shop in shops"
                    :key="shop.id"
                    :href="route('shops.show', shop.slug)"
                    class="group rounded-3xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                >
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-xl font-semibold text-gray-900 transition group-hover:text-indigo-600">
                            {{ shop.name }}
                        </h2>
                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600">
                            {{ shop.active_products_count }} товаров
                        </span>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-gray-600">
                        {{ shop.description || 'Описание магазина пока не добавлено.' }}
                    </p>
                    <span class="mt-6 inline-flex text-sm font-medium text-indigo-600">
                        Перейти в магазин →
                    </span>
                </Link>
            </div>
        </div>
    </MarketLayout>
</template>
