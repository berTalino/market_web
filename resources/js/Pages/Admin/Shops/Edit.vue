<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ShopForm from './Partials/ShopForm.vue';
import { Head } from '@inertiajs/vue3';
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
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Редактирование магазина
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Обновите данные, доступность и владельца магазина.
                </p>
            </div>
        </template>

        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <ShopForm
                :shop="shop"
                :owners="owners"
                :submit-url="route('admin.shops.update', shop.id)"
                submit-method="put"
                submit-label="Сохранить изменения"
            />
        </div>
    </AuthenticatedLayout>
</template>
