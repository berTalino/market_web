<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmptyState from '@/Components/EmptyState.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import { formatDateTime, formatPrice } from '@/utils/formatters';

const props = defineProps({
    products: {
        type: Array,
        default: () => [],
    },
});

const stats = computed(() => {
    const inventory = props.products.reduce((total, product) => total + Number(product.stock ?? 0), 0);

    return [
        {
            label: 'Всего товаров',
            value: props.products.length,
            note: 'Карточки каталога в системе',
        },
        {
            label: 'Активные',
            value: props.products.filter((product) => product.is_active).length,
            note: 'Доступны для публичной витрины',
        },
        {
            label: 'Без остатка',
            value: props.products.filter((product) => Number(product.stock ?? 0) === 0).length,
            note: 'Требуют пополнения или скрытия',
        },
        {
            label: 'Суммарный остаток',
            value: inventory,
            note: 'Единиц товаров по всем магазинам',
        },
    ];
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
                    <p class="section-eyebrow border-white/20 bg-white/10 text-white shadow-none">
                        Products control
                    </p>
                    <h1 class="mt-3 text-3xl font-semibold text-white sm:text-4xl">
                        Товары
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80">
                        Управление ассортиментом, ценами, остатками и доступностью товаров.
                    </p>
                </div>

                <Link
                    :href="route('admin.products.create')"
                    class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/15"
                >
                    Создать товар
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <section class="grid gap-4 xl:grid-cols-4">
                <article
                    v-for="item in stats"
                    :key="item.label"
                    class="elevated-card"
                >
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                        {{ item.label }}
                    </p>
                    <p class="mt-3 text-2xl font-semibold text-slate-950">
                        {{ item.value }}
                    </p>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        {{ item.note }}
                    </p>
                </article>
            </section>

            <EmptyState
                v-if="products.length === 0"
                title="Товаров пока нет"
                description="Создайте первый товар, чтобы наполнить каталог и дать пользователям возможность оформить заказ."
            >
                <Link
                    :href="route('admin.products.create')"
                    class="btn-primary"
                >
                    Создать товар
                </Link>
            </EmptyState>

            <section
                v-else
                class="surface-card overflow-hidden p-0"
            >
                <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="section-eyebrow">
                            Catalog table
                        </p>
                        <h2 class="mt-3 text-2xl font-semibold text-slate-950">
                            Матрица ассортимента
                        </h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Единая табличная поверхность для описаний, остатков, статусов публикации и переходов к редактированию.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <span class="badge-pill">{{ products.length }} SKU</span>
                        <span class="badge-pill bg-slate-50 text-slate-600">CRUD без изменений</span>
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
                                    Цена / остаток
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Статус
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Создан
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Действия
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr
                                v-for="product in products"
                                :key="product.id"
                                class="align-top transition hover:bg-slate-50/70"
                            >
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-slate-950">
                                        {{ product.name }}
                                    </div>
                                    <div class="mt-2 text-sm text-slate-500">
                                        {{ product.slug }}
                                    </div>
                                    <p
                                        v-if="product.description"
                                        class="mt-3 max-w-md text-sm leading-6 text-slate-500"
                                    >
                                        {{ product.description }}
                                    </p>
                                </td>

                                <td class="px-6 py-5 text-sm text-slate-600">
                                    <div class="font-medium text-slate-900">
                                        {{ product.shop?.name ?? '—' }}
                                    </div>
                                    <div class="mt-2 text-slate-500">
                                        {{ product.shop?.slug ?? '—' }}
                                    </div>
                                </td>

                                <td class="px-6 py-5 text-sm text-slate-600">
                                    <div class="font-semibold text-slate-950">
                                        {{ formatPrice(product.price) }}
                                    </div>
                                    <div class="mt-2 text-slate-500">
                                        Остаток: {{ product.stock }} шт.
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <StatusBadge
                                        :value="product.is_active ? 'active' : 'inactive'"
                                        :label="product.is_active ? 'Активен' : 'Скрыт'"
                                    />
                                </td>

                                <td class="px-6 py-5 text-sm text-slate-600">
                                    {{ formatDateTime(product.created_at) }}
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap justify-end gap-3">
                                        <Link
                                            v-if="product.shop?.slug"
                                            :href="route('products.show', { shop: product.shop.slug, product: product.slug })"
                                            class="text-sm font-medium text-slate-500 transition hover:text-slate-900"
                                        >
                                            Публичная страница
                                        </Link>
                                        <Link
                                            :href="route('admin.products.edit', product.id)"
                                            class="text-sm font-semibold text-brand-700 transition hover:text-brand-800"
                                        >
                                            Редактировать
                                        </Link>
                                        <button
                                            type="button"
                                            class="text-sm font-semibold text-rose-600 transition hover:text-rose-700"
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
            </section>
        </div>
    </AuthenticatedLayout>
</template>
