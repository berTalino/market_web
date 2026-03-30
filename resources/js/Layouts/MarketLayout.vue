<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';

const showingNavigationDropdown = ref(false);
const page = usePage();

const authUser = computed(() => page.props.auth?.user ?? null);
const authRole = computed(() => page.props.auth?.role ?? null);
const cartCount = computed(() => page.props.cart?.count ?? 0);
const currentUrl = computed(() => page.url ?? '/');
const isAdmin = computed(() => authRole.value === 'admin');

const navLinkClass = (active) => [
    'inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition',
    active
        ? 'bg-gray-900 text-white'
        : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900',
];

const mobileLinkClass = (active) => [
    'block rounded-xl px-4 py-3 text-sm font-medium transition',
    active
        ? 'bg-gray-900 text-white'
        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
];

const urlMatches = (prefixes) => prefixes.some((prefix) => prefix === '/' ? currentUrl.value === '/' : currentUrl.value.startsWith(prefix));
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-gray-900">
        <nav class="border-b border-gray-200 bg-white/95 backdrop-blur">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between gap-4">
                    <div class="flex items-center gap-6">
                        <Link
                            :href="route('shops.index')"
                            class="flex items-center gap-3"
                        >
                            <ApplicationLogo class="block h-9 w-auto fill-current text-indigo-600" />
                            <div>
                                <div class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">
                                    MVP
                                </div>
                                <div class="text-sm font-semibold text-gray-900">
                                    Market
                                </div>
                            </div>
                        </Link>

                        <div class="hidden items-center gap-2 md:flex">
                            <Link
                                :href="route('shops.index')"
                                :class="navLinkClass(urlMatches(['/', '/shops']))"
                            >
                                Магазины
                            </Link>
                            <Link
                                v-if="isAdmin"
                                :href="route('admin.shops.index')"
                                :class="navLinkClass(urlMatches(['/admin']))"
                            >
                                Админ-зона
                            </Link>
                            <Link
                                v-if="authUser"
                                :href="route('dashboard')"
                                :class="navLinkClass(urlMatches(['/dashboard']))"
                            >
                                Кабинет
                            </Link>
                        </div>
                    </div>

                    <div class="hidden items-center gap-3 md:flex">
                        <Link
                            :href="route('cart.index')"
                            class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:border-gray-300 hover:bg-gray-50"
                        >
                            <span>Корзина</span>
                            <span class="inline-flex min-w-6 items-center justify-center rounded-full bg-indigo-600 px-2 py-0.5 text-xs font-semibold text-white">
                                {{ cartCount }}
                            </span>
                        </Link>

                        <template v-if="authUser">
                            <Link
                                :href="route('profile.edit')"
                                class="text-sm font-medium text-gray-600 transition hover:text-gray-900"
                            >
                                Профиль
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="inline-flex items-center rounded-full bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700"
                            >
                                Выйти
                            </Link>
                        </template>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-sm font-medium text-gray-600 transition hover:text-gray-900"
                            >
                                Войти
                            </Link>
                            <Link
                                :href="route('register')"
                                class="inline-flex items-center rounded-full bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700"
                            >
                                Регистрация
                            </Link>
                        </template>
                    </div>

                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 p-2 text-gray-500 md:hidden"
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
                                :d="showingNavigationDropdown ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <div
                v-if="showingNavigationDropdown"
                class="border-t border-gray-200 px-4 py-4 md:hidden"
            >
                <div class="space-y-2">
                    <Link
                        :href="route('shops.index')"
                        :class="mobileLinkClass(urlMatches(['/', '/shops']))"
                    >
                        Магазины
                    </Link>
                    <Link
                        :href="route('cart.index')"
                        :class="mobileLinkClass(urlMatches(['/cart', '/checkout']))"
                    >
                        Корзина ({{ cartCount }})
                    </Link>
                    <Link
                        v-if="isAdmin"
                        :href="route('admin.shops.index')"
                        :class="mobileLinkClass(urlMatches(['/admin']))"
                    >
                        Админ-зона
                    </Link>
                    <Link
                        v-if="authUser"
                        :href="route('dashboard')"
                        :class="mobileLinkClass(urlMatches(['/dashboard']))"
                    >
                        Кабинет
                    </Link>
                    <Link
                        v-if="authUser"
                        :href="route('profile.edit')"
                        :class="mobileLinkClass(urlMatches(['/profile']))"
                    >
                        Профиль
                    </Link>
                    <Link
                        v-if="authUser"
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="block w-full rounded-xl bg-gray-900 px-4 py-3 text-left text-sm font-medium text-white"
                    >
                        Выйти
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            :class="mobileLinkClass(urlMatches(['/login']))"
                        >
                            Войти
                        </Link>
                        <Link
                            :href="route('register')"
                            :class="mobileLinkClass(urlMatches(['/register']))"
                        >
                            Регистрация
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <header
            v-if="$slots.header"
            class="border-b border-gray-200 bg-white"
        >
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <main class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <FlashMessage class="mb-6" />
            </div>
            <slot />
        </main>
    </div>
</template>
