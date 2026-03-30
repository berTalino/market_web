<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

const page = usePage();

const isAdmin = computed(() => page.props.auth?.role === 'admin');
const cartCount = computed(() => page.props.cart?.count ?? 0);

const cards = computed(() => {
    const items = [
        {
            title: 'Каталог магазинов',
            description: 'Перейти к публичной витрине маркетплейса.',
            href: route('shops.index'),
            action: 'Открыть каталог',
        },
        {
            title: 'Корзина',
            description: `Товаров в корзине: ${cartCount.value}.`,
            href: route('cart.index'),
            action: 'Открыть корзину',
        },
        {
            title: 'Профиль',
            description: 'Управление данными учётной записи.',
            href: route('profile.edit'),
            action: 'Открыть профиль',
        },
    ];

    if (isAdmin.value) {
        items.unshift(
            {
                title: 'Магазины',
                description: 'Управление магазинами маркетплейса.',
                href: route('admin.shops.index'),
                action: 'Открыть магазины',
            },
            {
                title: 'Товары',
                description: 'Управление ассортиментом и остатками.',
                href: route('admin.products.index'),
                action: 'Открыть товары',
            },
            {
                title: 'Заказы',
                description: 'Просмотр оформленных заказов клиентов.',
                href: route('admin.orders.index'),
                action: 'Открыть заказы',
            },
        );
    }

    return items;
});
</script>

<template>
    <Head title="Кабинет" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-900">
                        Кабинет
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Быстрый доступ к основным сценариям MVP-маркетплейса.
                    </p>
                </div>
            </div>
        </template>

        <div class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-6 rounded-2xl border border-indigo-100 bg-indigo-50 p-6">
                    <p class="text-sm font-medium text-indigo-700">
                        {{ $page.props.auth.user.name }}
                    </p>
                    <p class="mt-1 text-sm text-indigo-900">
                        Роль: {{ isAdmin ? 'администратор' : 'пользователь' }}
                    </p>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <Link
                        v-for="card in cards"
                        :key="card.href"
                        :href="card.href"
                        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ card.title }}
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            {{ card.description }}
                        </p>
                        <span class="mt-6 inline-flex text-sm font-medium text-indigo-600">
                            {{ card.action }} →
                        </span>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
