<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ShopForm from './Partials/ShopForm.vue';
import { Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps({
    owners: {
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
    <Head title="Админ · Создание магазина" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Новый магазин
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Заполните основные данные магазина для публикации в каталоге.
                </p>
            </div>
        </template>

        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <ShopForm
                :owners="owners"
                :defaults="defaults"
                :submit-url="route('admin.shops.store')"
                submit-label="Создать магазин"
            />
        </div>
    </AuthenticatedLayout>
</template>
