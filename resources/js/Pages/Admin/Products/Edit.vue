<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProductForm from './Partials/ProductForm.vue';
import { Head } from '@inertiajs/vue3';
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
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Редактирование товара
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Обновите цену, остаток и параметры отображения товара.
                </p>
            </div>
        </template>

        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <ProductForm
                :product="product"
                :shops="shops"
                :submit-url="route('admin.products.update', product.id)"
                submit-method="put"
                submit-label="Сохранить изменения"
            />
        </div>
    </AuthenticatedLayout>
</template>
