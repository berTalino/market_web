<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

const page = usePage();

const isAdmin = computed(() => page.props.auth?.role === 'admin');
const cartCount = computed(() => page.props.cart?.count ?? 0);
const userName = computed(() => page.props.auth?.user?.name ?? 'Пользователь');

const quickStats = computed(() => [
    {
        label: 'Роль в системе',
        value: isAdmin.value ? 'Администратор' : 'Покупатель',
        note: isAdmin.value
            ? 'Доступны административные разделы и управление данными.'
            : 'Доступны витрина, корзина и настройки аккаунта.',
    },
    {
        label: 'Корзина',
        value: `${cartCount.value} поз.`,
        note: 'Состояние корзины синхронизировано с storefront-flow.',
    },
    {
        label: 'Рабочий режим',
        value: isAdmin.value ? 'Operations' : 'Personal workspace',
        note: 'Новый shell визуально отделён от публичного layout.',
    },
]);

const cards = computed(() => {
    const items = [
        {
            title: 'Каталог магазинов',
            description: 'Перейти к публичной витрине маркетплейса.',
            href: route('shops.index'),
            action: 'Открыть каталог',
            tone: 'from-sky-500/15 to-brand-500/10',
        },
        {
            title: 'Корзина',
            description: `Товаров в корзине: ${cartCount.value}.`,
            href: route('cart.index'),
            action: 'Открыть корзину',
            tone: 'from-emerald-500/15 to-brand-500/10',
        },
        {
            title: 'Профиль',
            description: 'Управление данными учётной записи.',
            href: route('profile.edit'),
            action: 'Открыть профиль',
            tone: 'from-amber-500/15 to-brand-500/10',
        },
    ];

    if (isAdmin.value) {
        items.unshift(
            {
                title: 'Магазины',
                description: 'Управление магазинами маркетплейса.',
                href: route('admin.shops.index'),
                action: 'Открыть магазины',
                tone: 'from-fuchsia-500/15 to-brand-500/10',
            },
            {
                title: 'Товары',
                description: 'Управление ассортиментом и остатками.',
                href: route('admin.products.index'),
                action: 'Открыть товары',
                tone: 'from-indigo-500/15 to-blue-500/10',
            },
            {
                title: 'Заказы',
                description: 'Просмотр оформленных заказов клиентов.',
                href: route('admin.orders.index'),
                action: 'Открыть заказы',
                tone: 'from-rose-500/15 to-brand-500/10',
            },
        );
    }

    return items;
});

const operationalNotes = computed(() => [
    {
        title: 'Отдельный кабинетный shell',
        description: 'Навигация, hero-зона и рабочие поверхности изолированы от публичного storefront.',
    },
    {
        title: 'Единая система поверхностей',
        description: 'Карточки действий, сводки и quick blocks используют глобальные токены и классы дизайн-системы.',
    },
    {
        title: 'Сценарии без изменения логики',
        description: 'Все переходы продолжают использовать существующие маршруты Inertia и текущий backend CRUD.',
    },
]);
</script>

<template>
    <Head title="Кабинет" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="section-eyebrow border-white/20 bg-white/10 text-white shadow-none">
                        Workspace overview
                    </p>
                    <h2 class="mt-3 text-3xl font-semibold leading-tight text-white sm:text-4xl">
                        {{ userName }}, добро пожаловать в кабинет
                    </h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80">
                        Быстрый доступ к ключевым действиям маркетплейса в отдельной operational-среде.
                    </p>
                </div>

                <div class="rounded-brand-lg border border-white/15 bg-white/10 px-4 py-3 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.22em] text-white/60">
                        Активный режим
                    </p>
                    <p class="mt-2 text-lg font-semibold text-white">
                        {{ isAdmin ? 'Admin workspace' : 'Customer workspace' }}
                    </p>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <section class="grid gap-4 xl:grid-cols-3">
                <article
                    v-for="stat in quickStats"
                    :key="stat.label"
                    class="elevated-card"
                >
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                        {{ stat.label }}
                    </p>
                    <p class="mt-3 text-2xl font-semibold text-slate-950">
                        {{ stat.value }}
                    </p>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        {{ stat.note }}
                    </p>
                </article>
            </section>

            <section class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr),360px]">
                <div class="surface-card">
                    <div class="flex flex-col gap-3 border-b border-slate-100 pb-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="section-eyebrow">
                                Быстрые действия
                            </p>
                            <h3 class="mt-3 text-2xl font-semibold text-slate-950">
                                Основные сценарии кабинета
                            </h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Переходите к операциям, покупкам и настройкам без возврата в публичную витрину.
                            </p>
                        </div>

                        <div class="badge-pill">
                            {{ cards.length }} доступных разделов
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <Link
                            v-for="card in cards"
                            :key="card.href"
                            :href="card.href"
                            class="group relative overflow-hidden rounded-brand-lg border border-slate-200 bg-white p-5 shadow-soft transition duration-200 hover:-translate-y-1 hover:shadow-surface"
                        >
                            <div :class="`absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r ${card.tone}`" />

                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-950">
                                        {{ card.title }}
                                    </h4>
                                    <p class="mt-2 text-sm leading-6 text-slate-500">
                                        {{ card.description }}
                                    </p>
                                </div>

                                <span class="badge-pill shrink-0 bg-slate-50 text-slate-600">
                                    Go
                                </span>
                            </div>

                            <span class="mt-6 inline-flex items-center text-sm font-semibold text-brand-700 transition group-hover:text-brand-800">
                                {{ card.action }}
                                <span class="ml-2">→</span>
                            </span>
                        </Link>
                    </div>
                </div>

                <aside class="space-y-4">
                    <div class="elevated-card brand-gradient-bg shadow-brand">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-white/65">
                            Welcome panel
                        </p>
                        <h3 class="mt-3 text-2xl font-semibold text-white">
                            {{ isAdmin ? 'Управляйте платформой' : 'Контролируйте свои покупки' }}
                        </h3>
                        <p class="mt-3 text-sm leading-6 text-white/80">
                            Новый кабинетный интерфейс делает рабочие сценарии более собранными и визуально отделяет операционные действия от storefront-опыта.
                        </p>
                    </div>

                    <div class="surface-card">
                        <h3 class="text-lg font-semibold text-slate-950">
                            Что изменилось в UX
                        </h3>

                        <div class="mt-4 space-y-4">
                            <article
                                v-for="item in operationalNotes"
                                :key="item.title"
                                class="rounded-brand-md border border-slate-100 bg-slate-50/80 p-4"
                            >
                                <h4 class="text-sm font-semibold text-slate-900">
                                    {{ item.title }}
                                </h4>
                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    {{ item.description }}
                                </p>
                            </article>
                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
