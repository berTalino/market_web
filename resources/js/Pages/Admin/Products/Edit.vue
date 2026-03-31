<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProductForm from './Partials/ProductForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps({
    product: {
        type: Object,
        required: true,
    },
    shops: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head :title="`Админ · ${product.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="section-eyebrow border-white/20 bg-white/10 text-white shadow-none">
                        Product edit
                    </p>
                    <h1 class="mt-3 text-3xl font-semibold text-white sm:text-4xl">
                        Редактирование товара
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80">
                        Обновите цену, остаток и параметры отображения товара в новой операционной форме.
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
                :product="product"
                :shops="shops"
                :submit-url="route('admin.products.update', product.id)"
                submit-method="put"
                submit-label="Сохранить изменения"
            />

            <aside class="space-y-4">
                <div class="elevated-card">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                        Текущий объект
                    </p>
                    <h2 class="mt-3 text-2xl font-semibold text-slate-950">
                        {{ product.name }}
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Slug: {{ product.slug }}
                    </p>
                    <p class="mt-4 text-sm leading-6 text-slate-500">
                        Используйте форму для обновления товарных параметров без изменения существующих маршрутов и бизнес-правил.
                    </p>
                </div>

                <div class="surface-card">
                    <h2 class="text-lg font-semibold text-slate-950">
                        Навигация
                    </h2>
                    <div class="mt-4 space-y-3 text-sm">
                        <Link
                            :href="route('admin.products.index')"
                            class="btn-secondary w-full"
                        >
                            Вернуться к списку
                        </Link>
                        <Link
                            v-if="product.shop?.slug"
                            :href="route('products.show', { shop: product.shop.slug, product: product.slug })"
                            class="btn-primary w-full"
                        >
                            Открыть публичную карточку
                        </Link>
                    </div>
                </div>
            </aside>
        </div>
    </AuthenticatedLayout>
</template>
