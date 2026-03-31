<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmptyState from '@/Components/EmptyState.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import { formatDateTime } from '@/utils/formatters';

const props = defineProps({
    shops: {
        type: Array,
        default: () => [],
    },
});

const stats = computed(() => [
    {
        label: 'Всего магазинов',
        value: props.shops.length,
        note: 'Карточки магазинов в системе',
    },
    {
        label: 'Активные',
        value: props.shops.filter((shop) => shop.is_active).length,
        note: 'Показываются в публичном каталоге',
    },
    {
        label: 'С владельцем',
        value: props.shops.filter((shop) => shop.owner).length,
        note: 'Назначена ответственность за магазин',
    },
    {
        label: 'Без владельца',
        value: props.shops.filter((shop) => !shop.owner).length,
        note: 'Требуют назначения аккаунта',
    },
]);

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
                    <p class="section-eyebrow border-white/20 bg-white/10 text-white shadow-none">
                        Shops control
                    </p>
                    <h1 class="mt-3 text-3xl font-semibold text-white sm:text-4xl">
                        Магазины
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80">
                        Управление карточками магазинов и назначением владельцев.
                    </p>
                </div>

                <Link
                    :href="route('admin.shops.create')"
                    class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/15"
                >
                    Создать магазин
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
                v-if="shops.length === 0"
                title="Магазинов пока нет"
                description="Создайте первый магазин, чтобы он появился в админке и в публичном каталоге после активации."
            >
                <Link
                    :href="route('admin.shops.create')"
                    class="btn-primary"
                >
                    Создать магазин
                </Link>
            </EmptyState>

            <section
                v-else
                class="surface-card overflow-hidden p-0"
            >
                <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="section-eyebrow">
                            Directory table
                        </p>
                        <h2 class="mt-3 text-2xl font-semibold text-slate-950">
                            Реестр магазинов
                        </h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Светлая табличная поверхность для структуры маркетплейса, владельцев и статусов публикации.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <span class="badge-pill">{{ shops.length }} магазинов</span>
                        <span class="badge-pill bg-slate-50 text-slate-600">Inertia CRUD</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Магазин
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    Владелец
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
                                v-for="shop in shops"
                                :key="shop.id"
                                class="align-top transition hover:bg-slate-50/70"
                            >
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-slate-950">
                                        {{ shop.name }}
                                    </div>
                                    <div class="mt-2 text-sm text-slate-500">
                                        /shops/{{ shop.slug }}
                                    </div>
                                    <p
                                        v-if="shop.description"
                                        class="mt-3 max-w-md text-sm leading-6 text-slate-500"
                                    >
                                        {{ shop.description }}
                                    </p>
                                </td>

                                <td class="px-6 py-5 text-sm text-slate-600">
                                    <template v-if="shop.owner">
                                        <div class="font-medium text-slate-900">
                                            {{ shop.owner.name }}
                                        </div>
                                        <div class="mt-2">{{ shop.owner.email }}</div>
                                    </template>
                                    <span v-else class="text-slate-400">
                                        Не назначен
                                    </span>
                                </td>

                                <td class="px-6 py-5">
                                    <StatusBadge
                                        :value="shop.is_active ? 'active' : 'inactive'"
                                        :label="shop.is_active ? 'Активен' : 'Скрыт'"
                                    />
                                </td>

                                <td class="px-6 py-5 text-sm text-slate-600">
                                    {{ formatDateTime(shop.created_at) }}
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap justify-end gap-3">
                                        <Link
                                            :href="route('shops.show', shop.slug)"
                                            class="text-sm font-medium text-slate-500 transition hover:text-slate-900"
                                        >
                                            Публичная страница
                                        </Link>
                                        <Link
                                            :href="route('admin.shops.edit', shop.id)"
                                            class="text-sm font-semibold text-brand-700 transition hover:text-brand-800"
                                        >
                                            Редактировать
                                        </Link>
                                        <button
                                            type="button"
                                            class="text-sm font-semibold text-rose-600 transition hover:text-rose-700"
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
            </section>
        </div>
    </AuthenticatedLayout>
</template>
