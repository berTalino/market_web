<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProductForm from './Partials/ProductForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps({
    shops: {
        type: Array,
        default: () => [],
    },
    defaults: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <Head title="Админ · Создание товара" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="section-eyebrow border-white/20 bg-white/10 text-white shadow-none">
                        Product create
                    </p>
                    <h1 class="mt-3 text-3xl font-semibold text-white sm:text-4xl">
                        Новый товар
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80">
                        Создайте карточку товара для выбранного магазина в унифицированной административной форме.
                    </p>
                </div>

                <Link
                    :href="route('admin.products.index')"
                    class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/15"
                >
                    К списку товаров
                </Link>
            </div>
        </template>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr),320px]">
            <ProductForm
                :shops="shops"
                :defaults="defaults"
                :submit-url="route('admin.products.store')"
                submit-label="Создать товар"
            />

            <aside class="space-y-4">
                <div class="elevated-card brand-gradient-bg shadow-brand">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-white/65">
                        Workflow
                    </p>
                    <h2 class="mt-3 text-2xl font-semibold text-white">
                        Создание SKU
                    </h2>
                    <p class="mt-3 text-sm leading-6 text-white/80">
                        Все действия используют существующий CRUD и сохраняют текущий Inertia-flow без изменения бизнес-логики.
                    </p>
                </div>

                <div class="surface-card">
                    <h2 class="text-lg font-semibold text-slate-950">
                        Что заполнить
                    </h2>
                    <ul class="mt-4 space-y-3 text-sm leading-6 text-slate-500">
                        <li class="rounded-brand-md border border-slate-100 bg-slate-50/80 px-4 py-3">Привязку к магазину и корректный slug.</li>
                        <li class="rounded-brand-md border border-slate-100 bg-slate-50/80 px-4 py-3">Базовую цену и текущий остаток для MVP.</li>
                        <li class="rounded-brand-md border border-slate-100 bg-slate-50/80 px-4 py-3">Статус публикации для отображения в каталоге.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </AuthenticatedLayout>
</template>
