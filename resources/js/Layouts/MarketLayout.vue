<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { route } from 'ziggy-js';

const showingNavigationDropdown = ref(false);
const page = usePage();

const authUser = computed(() => page.props.auth?.user ?? null);
const authRole = computed(() => page.props.auth?.role ?? null);
const cartCount = computed(() => page.props.cart?.count ?? 0);
const currentUrl = computed(() => page.url ?? '/');
const currentYear = new Date().getFullYear();
const isAdmin = computed(() => authRole.value === 'admin');
const searchQuery = ref(String(page.props.searchQuery ?? ''));

watch(
    () => page.props.searchQuery,
    (value) => {
        searchQuery.value = String(value ?? '');
    },
);

const submitSearch = () => {
    const q = searchQuery.value.trim();

    router.get(
        route('shops.index'),
        q === '' ? {} : { q },
        {
            preserveScroll: true,
            replace: true,
        },
    );

    showingNavigationDropdown.value = false;
};

const urlMatches = (prefixes) => prefixes.some((prefix) => prefix === '/' ? currentUrl.value === '/' : currentUrl.value.startsWith(prefix));

const primaryLinks = computed(() => [
    {
        label: 'Главная',
        href: route('welcome'),
        active: currentUrl.value === '/',
    },
    {
        label: 'Магазины',
        href: route('shops.index'),
        active: urlMatches(['/shops']),
    },
    {
        label: 'Корзина',
        href: route('cart.index'),
        active: urlMatches(['/cart', '/checkout']),
    },
]);

const discoveryLinks = [
    {
        label: 'Преимущества',
        href: `${route('welcome')}#benefits`,
    },
    {
        label: 'Оплата',
        href: `${route('welcome')}#payments`,
    },
    {
        label: 'Категории',
        href: `${route('welcome')}#categories`,
    },
];

const navLinkClass = (active) => [
    'inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold transition duration-200',
    active
        ? 'bg-gradient-brand text-white shadow-brand'
        : 'text-slate-600 hover:bg-white hover:text-brand-700',
];

const mobileLinkClass = (active) => [
    'flex items-center justify-between rounded-[1.25rem] px-4 py-3 text-sm font-semibold transition duration-200',
    active
        ? 'bg-gradient-brand text-white shadow-brand'
        : 'bg-white text-slate-700 shadow-soft hover:text-brand-700',
];
</script>

<template>
    <div class="relative min-h-screen overflow-x-hidden text-slate-900">
        <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[34rem] bg-[radial-gradient(circle_at_top,_rgba(129,140,248,0.32),_transparent_42%),radial-gradient(circle_at_right,_rgba(14,165,233,0.16),_transparent_28%)]"></div>

        <div class="border-b border-white/60 bg-white/50 backdrop-blur-xl">
            <div class="section-container flex flex-wrap items-center justify-between gap-3 py-3 text-xs font-medium text-slate-500 sm:text-sm">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="badge-pill">Platio marketplace MVP</span>
                    <span class="hidden md:inline">
                        Безопасные платежные сценарии, мягкая витрина и быстрый путь от выбора до checkout.
                    </span>
                </div>

                <div class="hidden items-center gap-5 lg:flex">
                    <a
                        v-for="link in discoveryLinks"
                        :key="link.label"
                        :href="link.href"
                        class="hover:text-brand-700"
                    >
                        {{ link.label }}
                    </a>
                </div>
            </div>
        </div>

        <nav class="sticky top-0 z-40 border-b border-white/70 bg-white/75 backdrop-blur-2xl">
            <div class="section-container py-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex min-w-0 items-center gap-3 lg:flex-[0_0_auto]">
                        <Link
                            :href="route('welcome')"
                            class="flex items-center gap-3"
                        >
                            <div class="flex h-12 w-12 items-center justify-center rounded-[1.4rem] bg-gradient-brand text-white shadow-brand">
                                <ApplicationLogo class="h-7 w-auto fill-current" />
                            </div>

                            <div class="min-w-0">
                                <div class="truncate text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">
                                    Platio
                                </div>
                                <div class="truncate text-sm font-semibold text-slate-950 sm:text-base">
                                    Market storefront
                                </div>
                            </div>
                        </Link>
                    </div>

                    <div class="hidden flex-1 items-center gap-3 lg:flex">
                        <div class="flex items-center gap-1 rounded-full border border-white/80 bg-slate-950/[0.03] p-1 shadow-soft">
                            <Link
                                v-for="link in primaryLinks"
                                :key="link.label"
                                :href="link.href"
                                :class="navLinkClass(link.active)"
                            >
                                {{ link.label }}
                            </Link>
                        </div>

                        <form
                            class="flex min-w-0 flex-1 items-center gap-3 rounded-full border border-white/80 bg-white/90 px-4 py-3 text-sm text-slate-500 shadow-soft transition hover:border-brand-100 focus-within:border-brand-200 focus-within:text-brand-700"
                            @submit.prevent="submitSearch"
                        >
                            <svg
                                class="h-5 w-5 flex-none text-brand-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0Z"
                                />
                            </svg>
                            <input
                                v-model="searchQuery"
                                type="search"
                                name="q"
                                autocomplete="off"
                                class="min-w-0 flex-1 border-0 bg-transparent p-0 text-sm text-slate-700 placeholder:text-slate-400 focus:ring-0"
                                placeholder="Найти магазин, подборку или товар для быстрого заказа"
                            />
                            <button
                                type="submit"
                                class="inline-flex h-8 items-center rounded-full bg-brand-600 px-3 text-xs font-semibold text-white transition hover:bg-brand-700"
                            >
                                Найти
                            </button>
                        </form>
                    </div>

                    <div class="hidden items-center gap-2 md:flex lg:flex-[0_0_auto]">
                        <Link
                            :href="route('cart.index')"
                            class="btn-secondary px-4 py-2.5"
                        >
                            <span>Корзина</span>
                            <span class="inline-flex min-w-7 items-center justify-center rounded-full bg-brand-600 px-2 py-1 text-xs font-semibold text-white">
                                {{ cartCount }}
                            </span>
                        </Link>

                        <template v-if="authUser">
                            <Link
                                v-if="isAdmin"
                                :href="route('admin.shops.index')"
                                class="btn-ghost px-4 py-2.5"
                            >
                                Админ
                            </Link>
                            <Link
                                :href="route('dashboard')"
                                class="btn-ghost px-4 py-2.5"
                            >
                                Кабинет
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="btn-primary px-4 py-2.5"
                            >
                                Выйти
                            </Link>
                        </template>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="btn-ghost px-4 py-2.5"
                            >
                                Войти
                            </Link>
                            <Link
                                :href="route('register')"
                                class="btn-primary px-4 py-2.5"
                            >
                                Стать клиентом
                            </Link>
                        </template>
                    </div>

                    <button
                        type="button"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/80 bg-white text-slate-600 shadow-soft md:hidden"
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                    >
                        <svg
                            class="h-6 w-6"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                :d="showingNavigationDropdown ? 'M6 18L18 6M6 6l12 12' : 'M4 7h16M4 12h16M4 17h16'"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <div
                v-if="showingNavigationDropdown"
                class="border-t border-white/70 bg-white/90 md:hidden"
            >
                <div class="section-container space-y-3 py-4">
                    <form
                        class="field-shell w-full justify-start text-sm text-slate-500"
                        @submit.prevent="submitSearch"
                    >
                        <svg
                            class="h-5 w-5 flex-none text-brand-500"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0Z"
                            />
                        </svg>
                        <input
                            v-model="searchQuery"
                            type="search"
                            name="q"
                            autocomplete="off"
                            placeholder="Открыть каталог магазинов"
                        />
                        <button
                            type="submit"
                            class="btn-secondary px-3 py-2 text-xs"
                        >
                            Найти
                        </button>
                    </form>

                    <div class="space-y-2">
                        <Link
                            v-for="link in primaryLinks"
                            :key="link.label"
                            :href="link.href"
                            :class="mobileLinkClass(link.active)"
                        >
                            <span>{{ link.label }}</span>
                            <span class="text-xs opacity-70">→</span>
                        </Link>
                    </div>

                    <div class="grid gap-2 sm:grid-cols-3">
                        <a
                            v-for="link in discoveryLinks"
                            :key="link.label"
                            :href="link.href"
                            class="surface-card px-4 py-3 text-sm font-semibold text-slate-700"
                        >
                            {{ link.label }}
                        </a>
                    </div>

                    <div class="grid gap-2">
                        <template v-if="authUser">
                            <Link
                                :href="route('dashboard')"
                                class="btn-secondary w-full"
                            >
                                Кабинет
                            </Link>
                            <Link
                                v-if="isAdmin"
                                :href="route('admin.shops.index')"
                                class="btn-secondary w-full"
                            >
                                Админ-зона
                            </Link>
                            <Link
                                :href="route('profile.edit')"
                                class="btn-ghost w-full justify-center"
                            >
                                Профиль
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="btn-primary w-full"
                            >
                                Выйти
                            </Link>
                        </template>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="btn-secondary w-full"
                            >
                                Войти
                            </Link>
                            <Link
                                :href="route('register')"
                                class="btn-primary w-full"
                            >
                                Регистрация
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <header
            v-if="$slots.header"
            class="pt-6 sm:pt-8"
        >
            <div class="section-container">
                <div class="surface-card overflow-hidden px-6 py-6 sm:px-8 sm:py-7">
                    <slot name="header" />
                </div>
            </div>
        </header>

        <main class="relative flex-1 pb-16 pt-6 sm:pb-20 sm:pt-8">
            <div class="section-container">
                <FlashMessage class="mb-6" />
            </div>

            <slot />
        </main>

        <footer class="border-t border-white/10 bg-slate-950 text-white">
            <div class="section-container py-12 sm:py-16">
                <div class="grid gap-10 lg:grid-cols-[1.15fr,0.85fr,0.85fr,0.95fr]">
                    <div class="space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-[1.4rem] bg-white/10 text-white">
                                <ApplicationLogo class="h-7 w-auto fill-current" />
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.28em] text-brand-200">
                                    Platio
                                </div>
                                <div class="text-lg font-semibold text-white">
                                    Public marketplace shell
                                </div>
                            </div>
                        </div>

                        <p class="max-w-sm text-sm leading-7 text-white/70">
                            Эмоциональная витрина для маркетплейса с акцентом на доверие, быстрый выбор магазина и плавный переход к оплате.
                        </p>

                        <div class="flex flex-wrap gap-2 text-xs font-semibold text-white/80">
                            <span class="rounded-full border border-white/10 px-3 py-1.5">Secure checkout</span>
                            <span class="rounded-full border border-white/10 px-3 py-1.5">Marketplace UX</span>
                            <span class="rounded-full border border-white/10 px-3 py-1.5">Brand-first UI</span>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-[0.24em] text-white/50">
                            Покупателям
                        </h2>
                        <div class="mt-5 space-y-3 text-sm text-white/70">
                            <Link :href="route('welcome')" class="block hover:text-white">Главная</Link>
                            <Link :href="route('shops.index')" class="block hover:text-white">Каталог магазинов</Link>
                            <Link :href="route('cart.index')" class="block hover:text-white">Корзина</Link>
                            <Link v-if="authUser" :href="route('profile.edit')" class="block hover:text-white">Профиль</Link>
                            <Link v-else :href="route('login')" class="block hover:text-white">Вход в аккаунт</Link>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-[0.24em] text-white/50">
                            Витрина
                        </h2>
                        <div class="mt-5 space-y-3 text-sm text-white/70">
                            <a :href="`${route('welcome')}#benefits`" class="block hover:text-white">Почему Platio</a>
                            <a :href="`${route('welcome')}#payments`" class="block hover:text-white">Платежные сценарии</a>
                            <a :href="`${route('welcome')}#categories`" class="block hover:text-white">Категории и подборки</a>
                            <Link v-if="authUser" :href="route('dashboard')" class="block hover:text-white">Личный кабинет</Link>
                            <Link v-else :href="route('register')" class="block hover:text-white">Регистрация</Link>
                        </div>
                    </div>

                    <div class="space-y-4 rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                        <h2 class="text-sm font-semibold uppercase tracking-[0.24em] text-white/50">
                            Доверие и скорость
                        </h2>
                        <div class="space-y-3 text-sm text-white/70">
                            <p>Оплата онлайн, переход к checkout без лишних шагов и понятная витрина для повторных покупок.</p>
                            <p>Карточки, CTA и навигация собраны на базе глобальных брендовых utility-классов.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10">
                <div class="section-container flex flex-col gap-2 py-5 text-xs text-white/45 sm:flex-row sm:items-center sm:justify-between">
                    <span>© {{ currentYear }} Platio Market MVP</span>
                    <span>Публичный layout для storefront, каталога и корзины</span>
                </div>
            </div>
        </footer>
    </div>
</template>
