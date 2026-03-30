<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmptyState from '@/Components/EmptyState.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { formatDateTime } from '@/utils/formatters';

defineProps({
    shops: {
        type: Array,
        default: () => [],
    },
});

const destroyShop = (shopId) => {
    router.delete(route('admin.shops.destroy', shopId), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Админ · Магазины" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">
                        Магазины
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Управление карточками магазинов и назначением владельцев.
                    </p>
                </div>

                <Link
                    :href="route('admin.shops.create')"
                    class="inline-flex items-center justify-center rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700"
                >
                    Создать магазин
                </Link>
            </div>
        </template>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <EmptyState
                v-if="shops.length === 0"
                title="Магазинов пока нет"
                description="Создайте первый магазин, чтобы он появился в админке и в публичном каталоге после активации."
            >
                <Link
                    :href="route('admin.shops.create')"
                    class="inline-flex items-center rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700"
                >
                    Создать магазин
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
                                    Магазин
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                    Владелец
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
                                v-for="shop in shops"
                                :key="shop.id"
                                class="align-top"
                            >
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-gray-900">
                                        {{ shop.name }}
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        /shops/{{ shop.slug }}
                                    </div>
                                    <p
                                        v-if="shop.description"
                                        class="mt-2 max-w-md text-sm leading-6 text-gray-500"
                                    >
                                        {{ shop.description }}
                                    </p>
                                </td>
                                <td class="px-6 py-5 text-sm text-gray-600">
                                    <template v-if="shop.owner">
                                        <div class="font-medium text-gray-900">
                                            {{ shop.owner.name }}
                                        </div>
                                        <div>{{ shop.owner.email }}</div>
                                    </template>
                                    <span v-else class="text-gray-400">
                                        Не назначен
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <StatusBadge
                                        :value="shop.is_active ? 'active' : 'inactive'"
                                        :label="shop.is_active ? 'Активен' : 'Скрыт'"
                                    />
                                </td>
                                <td class="px-6 py-5 text-sm text-gray-600">
                                    {{ formatDateTime(shop.created_at) }}
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap justify-end gap-3">
                                        <Link
                                            :href="route('shops.show', shop.slug)"
                                            class="text-sm font-medium text-gray-500 transition hover:text-gray-900"
                                        >
                                            Публичная страница
                                        </Link>
                                        <Link
                                            :href="route('admin.shops.edit', shop.id)"
                                            class="text-sm font-medium text-indigo-600 transition hover:text-indigo-700"
                                        >
                                            Редактировать
                                        </Link>
                                        <button
                                            type="button"
                                            class="text-sm font-medium text-rose-600 transition hover:text-rose-700"
                                            @click="destroyShop(shop.id)"
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
