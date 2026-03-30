<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmptyState from '@/Components/EmptyState.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { formatDateTime, formatPrice } from '@/utils/formatters';

defineProps({
    products: {
        type: Array,
        default: () => [],
    },
});

const destroyProduct = (productId) => {
    router.delete(route('admin.products.destroy', productId), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Админ · Товары" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">
                        Товары
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Управление ассортиментом, ценами, остатками и доступностью товаров.
                    </p>
                </div>

                <Link
                    :href="route('admin.products.create')"
                    class="inline-flex items-center justify-center rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700"
                >
                    Создать товар
                </Link>
            </div>
        </template>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <EmptyState
                v-if="products.length === 0"
                title="Товаров пока нет"
                description="Создайте первый товар, чтобы наполнить каталог и дать пользователям возможность оформить заказ."
            >
                <Link
                    :href="route('admin.products.create')"
                    class="inline-flex items-center rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700"
                >
                    Создать товар
                </Link>
            </EmptyState>

            <div
                v-else
                class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm"
            >
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
                                    Цена / остаток
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                    Статус
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                    Создан
                                </th>
                                <th class="px-6 py-4" />
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr
                                v-for="product in products"
                                :key="product.id"
                                class="align-top"
                            >
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-gray-900">
                                        {{ product.name }}
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        {{ product.slug }}
                                    </div>
                                    <p
                                        v-if="product.description"
                                        class="mt-2 max-w-md text-sm leading-6 text-gray-500"
                                    >
                                        {{ product.description }}
                                    </p>
                                </td>
                                <td class="px-6 py-5 text-sm text-gray-600">
                                    <div class="font-medium text-gray-900">
                                        {{ product.shop?.name ?? '—' }}
                                    </div>
                                    <div class="mt-1 text-gray-500">
                                        {{ product.shop?.slug ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm text-gray-600">
                                    <div class="font-medium text-gray-900">
                                        {{ formatPrice(product.price) }}
                                    </div>
                                    <div class="mt-1 text-gray-500">
                                        Остаток: {{ product.stock }} шт.
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <StatusBadge
                                        :value="product.is_active ? 'active' : 'inactive'"
                                        :label="product.is_active ? 'Активен' : 'Скрыт'"
                                    />
                                </td>
                                <td class="px-6 py-5 text-sm text-gray-600">
                                    {{ formatDateTime(product.created_at) }}
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap justify-end gap-3">
                                        <Link
                                            v-if="product.shop?.slug"
                                            :href="route('products.show', { shop: product.shop.slug, product: product.slug })"
                                            class="text-sm font-medium text-gray-500 transition hover:text-gray-900"
                                        >
                                            Публичная страница
                                        </Link>
                                        <Link
                                            :href="route('admin.products.edit', product.id)"
                                            class="text-sm font-medium text-indigo-600 transition hover:text-indigo-700"
                                        >
                                            Редактировать
                                        </Link>
                                        <button
                                            type="button"
                                            class="text-sm font-medium text-rose-600 transition hover:text-rose-700"
                                            @click="destroyProduct(product.id)"
                                        >
                                            Удалить
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
