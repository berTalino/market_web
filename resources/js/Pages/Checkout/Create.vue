<script setup>
import FlashMessage from '@/Components/FlashMessage.vue';
import InputError from '@/Components/InputError.vue';
import MarketLayout from '@/Layouts/MarketLayout.vue';
import { formatPrice } from '@/utils/formatters';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps({
    cart: {
        type: Object,
        required: true,
    },
    defaults: {
        type: Object,
        default: () => ({}),
    },
});

const page = usePage();

const form = useForm({
    customer_name: props.defaults.customer_name ?? '',
    customer_phone: props.defaults.customer_phone ?? '',
    customer_email: props.defaults.customer_email ?? '',
    comment: props.defaults.comment ?? '',
});

const successMessage = computed(() => page.props.flash?.success ?? null);

const submit = () => {
    form.transform((data) => ({
        ...data,
        customer_email: data.customer_email ? data.customer_email : null,
        comment: data.comment ? data.comment : null,
    })).post(route('checkout.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Оформление заказа" />

    <MarketLayout>
        <div class="section-container pb-12 pt-6">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm text-slate-400">
                <Link
                    :href="route('shops.index')"
                    class="hover:text-brand-600 transition"
                >
                    Каталог
                </Link>
                <span>→</span>
                <Link
                    :href="route('cart.index')"
                    class="hover:text-brand-600 transition"
                >
                    Корзина
                </Link>
                <span>→</span>
                <span class="text-slate-700 font-medium">Оформление</span>
            </nav>

            <!-- Success message -->
            <div
                v-if="successMessage"
                class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 animate-fade-in"
            >
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-emerald-800">{{ successMessage }}</p>
                </div>
            </div>

            <!-- Empty cart -->
            <div
                v-if="cart.is_empty && !successMessage"
                class="flex flex-col items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-16 text-center shadow-sm"
            >
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                    <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-slate-900">Корзина пуста</h2>
                <p class="mt-2 max-w-sm text-sm text-slate-500">
                    Добавьте товары в корзину, чтобы оформить заказ
                </p>
                <Link
                    :href="route('shops.index')"
                    class="btn-primary mt-6"
                >
                    Перейти в каталог
                </Link>
            </div>

            <!-- Checkout form + Order summary -->
            <div
                v-else-if="!cart.is_empty"
                class="grid gap-6 lg:grid-cols-[1fr,22rem] xl:grid-cols-[1fr,24rem]"
            >
                <!-- Left: Form -->
                <div class="space-y-5">
                    <h1 class="text-2xl font-semibold text-slate-900">Оформление заказа</h1>

                    <FlashMessage class="mb-2" />

                    <form
                        class="space-y-5"
                        @submit.prevent="submit"
                    >
                        <!-- Contact info -->
                        <fieldset class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                            <legend class="sr-only">Контактные данные</legend>
                            <h2 class="mb-5 text-base font-semibold text-slate-800">Контактные данные</h2>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="space-y-1.5 sm:col-span-2">
                                    <label
                                        for="customer_name"
                                        class="text-sm font-medium text-slate-700"
                                    >
                                        Имя <span class="text-red-400">*</span>
                                    </label>
                                    <input
                                        id="customer_name"
                                        v-model="form.customer_name"
                                        type="text"
                                        placeholder="Иван Петров"
                                        class="checkout-input"
                                    >
                                    <InputError :message="form.errors.customer_name" />
                                </div>

                                <div class="space-y-1.5">
                                    <label
                                        for="customer_phone"
                                        class="text-sm font-medium text-slate-700"
                                    >
                                        Телефон <span class="text-red-400">*</span>
                                    </label>
                                    <input
                                        id="customer_phone"
                                        v-model="form.customer_phone"
                                        type="tel"
                                        placeholder="+7 900 123-45-67"
                                        class="checkout-input"
                                    >
                                    <InputError :message="form.errors.customer_phone" />
                                </div>

                                <div class="space-y-1.5">
                                    <label
                                        for="customer_email"
                                        class="text-sm font-medium text-slate-700"
                                    >
                                        Email
                                    </label>
                                    <input
                                        id="customer_email"
                                        v-model="form.customer_email"
                                        type="email"
                                        placeholder="name@example.com"
                                        class="checkout-input"
                                    >
                                    <InputError :message="form.errors.customer_email" />
                                </div>
                            </div>
                        </fieldset>

                        <!-- Comment -->
                        <fieldset class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                            <legend class="sr-only">Комментарий</legend>
                            <h2 class="mb-4 text-base font-semibold text-slate-800">Комментарий к заказу</h2>

                            <textarea
                                v-model="form.comment"
                                rows="3"
                                placeholder="Например, позвонить перед доставкой"
                                class="checkout-input resize-none"
                            ></textarea>
                            <InputError :message="form.errors.comment || form.errors.cart" />
                        </fieldset>

                        <!-- Submit (mobile) — shown below form on small screens -->
                        <div class="lg:hidden">
                            <button
                                type="submit"
                                class="btn-primary w-full text-base"
                                :disabled="form.processing"
                            >
                                {{ form.processing ? 'Оформляем...' : `Оформить заказ · ${formatPrice(cart.total_amount)}` }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Order summary (sticky) -->
                <aside class="lg:sticky lg:top-24 lg:self-start">
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-5 sm:p-6">
                            <h2 class="text-base font-semibold text-slate-800">Ваш заказ</h2>

                            <!-- Items -->
                            <ul class="mt-4 divide-y divide-slate-100">
                                <li
                                    v-for="item in cart.items"
                                    :key="item.product_id"
                                    class="flex items-start justify-between gap-3 py-3 first:pt-0 last:pb-0"
                                >
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-900 leading-snug">
                                            {{ item.name }}
                                        </p>
                                        <p class="mt-0.5 text-xs text-slate-400">
                                            {{ item.quantity }} шт. × {{ formatPrice(item.price) }}
                                        </p>
                                    </div>
                                    <span class="shrink-0 text-sm font-semibold text-slate-900">
                                        {{ formatPrice(item.line_total) }}
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <!-- Totals -->
                        <div class="border-t border-slate-100 bg-slate-50/60 p-5 sm:p-6">
                            <dl class="space-y-2 text-sm">
                                <div class="flex justify-between text-slate-500">
                                    <dt>Товаров</dt>
                                    <dd>{{ cart.count }} шт.</dd>
                                </div>
                                <div class="flex justify-between text-slate-500">
                                    <dt>Подытог</dt>
                                    <dd>{{ formatPrice(cart.subtotal_amount) }}</dd>
                                </div>
                                <div class="flex justify-between border-t border-slate-200 pt-3 text-base font-semibold text-slate-900">
                                    <dt>Итого</dt>
                                    <dd>{{ formatPrice(cart.total_amount) }}</dd>
                                </div>
                            </dl>

                            <!-- Submit (desktop) -->
                            <button
                                type="submit"
                                form="checkout-form"
                                class="btn-primary mt-4 hidden w-full text-base lg:flex"
                                :disabled="form.processing"
                                @click="submit"
                            >
                                {{ form.processing ? 'Оформляем...' : 'Оформить заказ' }}
                            </button>

                            <Link
                                :href="route('cart.index')"
                                class="mt-3 block text-center text-sm text-slate-400 hover:text-brand-600 transition"
                            >
                                ← Вернуться в корзину
                            </Link>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </MarketLayout>
</template>

<style scoped>
.checkout-input {
    @apply w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 shadow-none transition duration-150;
    @apply focus:border-brand-400 focus:ring-2 focus:ring-brand-100 focus:outline-none;
}
</style>
