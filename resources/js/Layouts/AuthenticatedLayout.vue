<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

const page = usePage();

const user = computed(() => page.props.auth?.user ?? {});
const role = computed(() => page.props.auth?.role ?? 'user');
const cartCount = computed(() => page.props.cart?.count ?? 0);
const isAdmin = computed(() => role.value === 'admin');

const isCurrent = (patterns = []) => patterns.some((pattern) => route().current(pattern));

const navigationGroups = computed(() => {
    const groups = [
        {
            label: 'Кабинет',
            items: [
                {
                    label: 'Обзор',
                    href: route('dashboard'),
                    patterns: ['dashboard'],
                    caption: 'Главная рабочая панель',
                },
                {
                    label: 'Профиль',
                    href: route('profile.edit'),
                    patterns: ['profile.edit'],
                    caption: 'Настройки аккаунта',
                },
            ],
        },
        {
            label: 'Покупки',
            items: [
                {
                    label: 'Каталог',
                    href: route('shops.index'),
                    patterns: ['shops.index', 'shops.show', 'products.show'],
                    caption: 'Публичная витрина',
                },
                {
                    label: 'Корзина',
                    href: route('cart.index'),
                    patterns: ['cart.index', 'checkout.create'],
                    caption: `Товаров: ${cartCount.value}`,
                },
            ],
        },
    ];

    if (!isAdmin.value) {
        return groups;
    }

    return [
        ...groups,
        {
            label: 'Операции',
            items: [
                {
                    label: 'Магазины',
                    href: route('admin.shops.index'),
                    patterns: ['admin.shops.*'],
                    caption: 'Структура витрины',
                },
                {
                    label: 'Товары',
                    href: route('admin.products.index'),
                    patterns: ['admin.products.*'],
                    caption: 'Ассортимент и остатки',
                },
                {
                    label: 'Заказы',
                    href: route('admin.orders.index'),
                    patterns: ['admin.orders.*'],
                    caption: 'Checkout и статусы',
                },
            ],
        },
    ];
});

const shellMeta = computed(() => {
    if (isAdmin.value) {
        return {
            eyebrow: 'Admin workspace',
            title: 'Операционная панель маркетплейса',
            description: 'Управление магазинами, товарами и заказами в отдельном product-oriented shell.',
        };
    }

    return {
        eyebrow: 'User workspace',
        title: 'Личный кабинет клиента',
        description: 'Отдельная кабинетка для управления покупками, корзиной и профилем без визуальной зависимости от storefront.',
    };
});
</script>

<template>
    <div class="admin-panel-shell">
        <div class="admin-content-shell lg:grid lg:grid-cols-[280px,minmax(0,1fr)] lg:gap-8">
            <aside class="mb-6 lg:sticky lg:top-6 lg:mb-0 lg:self-start">
                <div class="space-y-4">
                    <div class="brand-gradient-bg rounded-brand-xl p-6 shadow-brand-lg">
                        <p class="section-eyebrow border-white/20 bg-white/10 text-white shadow-none">
                            {{ shellMeta.eyebrow }}
                        </p>
                        <h1 class="mt-4 text-2xl font-semibold text-white">
                            Platio Market
                        </h1>
                        <p class="mt-2 text-sm leading-6 text-white/80">
                            {{ shellMeta.description }}
                        </p>

                        <div class="mt-6 rounded-brand-lg border border-white/15 bg-white/10 p-4 backdrop-blur">
                            <p class="text-sm font-semibold text-white">
                                {{ user.name }}
                            </p>
                            <p class="mt-1 text-sm text-white/70">
                                {{ isAdmin ? 'Администратор платформы' : 'Пользователь маркетплейса' }}
                            </p>
                        </div>
                    </div>

                    <div
                        v-for="group in navigationGroups"
                        :key="group.label"
                        class="elevated-card p-3"
                    >
                        <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                            {{ group.label }}
                        </p>

                        <nav class="space-y-1.5">
                            <Link
                                v-for="item in group.items"
                                :key="item.href"
                                :href="item.href"
                                :class="[
                                    'block rounded-brand-md px-3 py-3 transition duration-200',
                                    isCurrent(item.patterns)
                                        ? 'bg-brand-50 text-brand-800 shadow-soft ring-1 ring-brand-100'
                                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900',
                                ]"
                            >
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-semibold">{{ item.label }}</span>
                                    <span
                                        v-if="isCurrent(item.patterns)"
                                        class="h-2.5 w-2.5 rounded-full bg-brand-500"
                                    />
                                </div>
                                <p class="mt-1 text-xs text-slate-400">
                                    {{ item.caption }}
                                </p>
                            </Link>
                        </nav>
                    </div>

                    <div class="surface-card p-4">
                        <p class="text-sm font-semibold text-slate-900">
                            Быстрый выход
                        </p>
                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Сессия, доступы и сценарии остаются прежними — обновлён только shell кабинета.
                        </p>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="btn-secondary mt-4 w-full"
                        >
                            Выйти из аккаунта
                        </Link>
                    </div>
                </div>
            </aside>

            <main class="min-w-0 space-y-6">
                <section class="overflow-hidden rounded-brand-xl bg-gradient-to-br from-brand-700 via-violet-700 to-blue-600 shadow-surface">
                    <div class="grid gap-6 px-6 py-6 sm:px-8 lg:grid-cols-[minmax(0,1fr),220px] lg:items-end">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">
                                {{ shellMeta.eyebrow }}
                            </p>

                            <div
                                v-if="$slots.header"
                                class="mt-3 text-white [&_h1]:text-white [&_h2]:text-white [&_p]:text-white/80"
                            >
                                <slot name="header" />
                            </div>

                            <div v-else class="mt-3">
                                <h2 class="text-3xl font-semibold text-white">
                                    {{ shellMeta.title }}
                                </h2>
                                <p class="mt-2 max-w-2xl text-sm leading-6 text-white/80">
                                    {{ shellMeta.description }}
                                </p>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                            <div class="rounded-brand-lg border border-white/15 bg-white/10 p-4 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.22em] text-white/60">
                                    Роль
                                </p>
                                <p class="mt-2 text-lg font-semibold text-white">
                                    {{ isAdmin ? 'Admin' : 'Customer' }}
                                </p>
                            </div>

                            <div class="rounded-brand-lg border border-white/15 bg-white/10 p-4 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.22em] text-white/60">
                                    Корзина
                                </p>
                                <p class="mt-2 text-lg font-semibold text-white">
                                    {{ cartCount }} поз.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <slot />
            </main>
        </div>
    </div>
</template>
