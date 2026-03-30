<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProductForm from './Partials/ProductForm.vue';
import { Head } from '@inertiajs/vue3';
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
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Новый товар
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Создайте карточку товара для выбранного магазина.
                </p>
            </div>
        </template>

        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <ProductForm
                :shops="shops"
                :defaults="defaults"
                :submit-url="route('admin.products.store')"
                submit-label="Создать товар"
            />
        </div>
    </AuthenticatedLayout>
</template>
