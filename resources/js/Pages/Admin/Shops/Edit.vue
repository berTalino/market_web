<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ShopForm from './Partials/ShopForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps({
    shop: {
        type: Object,
        required: true,
    },
    owners: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head :title="`Админ · ${shop.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="section-eyebrow border-white/20 bg-white/10 text-white shadow-none">
                        Shop edit
                    </p>
                    <h1 class="mt-3 text-3xl font-semibold text-white sm:text-4xl">
                        Редактирование магазина
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80">
                        Обновите данные, доступность и владельца магазина в новой административной форме.
                    </p>
                </div>

                <Link
                    :href="route('admin.shops.index')"
                    class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/15"
                >
                    К списку магазинов
                </Link>
            </div>
        </template>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr),320px]">
            <ShopForm
                :shop="shop"
                :owners="owners"
                :submit-url="route('admin.shops.update', shop.id)"
                submit-method="put"
                submit-label="Сохранить изменения"
            />

            <aside class="space-y-4">
                <div class="elevated-card">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                        Текущий объект
                    </p>
                    <h2 class="mt-3 text-2xl font-semibold text-slate-950">
                        {{ shop.name }}
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Slug: {{ shop.slug }}
                    </p>
                    <p class="mt-4 text-sm leading-6 text-slate-500">
                        Изменения сохраняются через существующие update-маршруты, поэтому серверное поведение остаётся прежним.
                    </p>
                </div>

                <div class="surface-card">
                    <h2 class="text-lg font-semibold text-slate-950">
                        Навигация
                    </h2>
                    <div class="mt-4 space-y-3 text-sm">
                        <Link
                            :href="route('admin.shops.index')"
                            class="btn-secondary w-full"
                        >
                            Вернуться к списку
                        </Link>
                        <Link
                            :href="route('shops.show', shop.slug)"
                            class="btn-primary w-full"
                        >
                            Открыть публичную страницу
                        </Link>
                    </div>
                </div>
            </aside>
        </div>
    </AuthenticatedLayout>
</template>
