<script setup>
import EmptyState from '@/Components/EmptyState.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import MarketLayout from '@/Layouts/MarketLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import { formatPrice } from '@/utils/formatters';

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
        <template #header>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900">
                        Оформление заказа
                    </h1>
                    <p class="mt-3 text-base leading-7 text-gray-600">
                        Укажите контактные данные и подтвердите заказ.
                    </p>
                </div>

                <Link
                    :href="route('cart.index')"
                    class="inline-flex items-center text-sm font-medium text-indigo-600 transition hover:text-indigo-700"
                >
                    ← Вернуться в корзину
                </Link>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div
                v-if="successMessage"
                class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6"
            >
                <h2 class="text-lg font-semibold text-emerald-900">
                    Заказ успешно оформлен
                </h2>
                <p class="mt-2 text-sm leading-6 text-emerald-800">
                    {{ successMessage }} Мы сохранили заказ через текущий backend flow, а корзина обновлена.
                </p>
            </div>

            <EmptyState
                v-if="cart.is_empty"
                title="Нет товаров для оформления"
                description="Вернитесь в каталог, добавьте товары в корзину и повторите оформление."
            >
                <Link
                    :href="route('shops.index')"
                    class="inline-flex items-center rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700"
                >
                    Перейти в каталог
                </Link>
            </EmptyState>

            <div
                v-else
                class="grid gap-6 lg:grid-cols-[1fr,0.8fr]"
            >
                <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Контактные данные
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Эти данные будут сохранены в заказе и доступны администратору.
                        </p>
                    </div>

                    <FlashMessage class="mb-6" />

                    <form
                        class="space-y-6"
                        @submit.prevent="submit"
                    >
                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Имя
                                </label>
                                <input
                                    v-model="form.customer_name"
                                    type="text"
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Иван Петров"
                                >
                                <InputError :message="form.errors.customer_name" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Телефон
                                </label>
                                <input
                                    v-model="form.customer_phone"
                                    type="text"
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="+7 900 123-45-67"
                                >
                                <InputError :message="form.errors.customer_phone" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Email
                                </label>
                                <input
                                    v-model="form.customer_email"
                                    type="email"
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="name@example.com"
                                >
                                <InputError :message="form.errors.customer_email" />
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Комментарий к заказу
                                </label>
                                <textarea
                                    v-model="form.comment"
                                    rows="4"
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Например, позвонить перед доставкой"
                                />
                                <InputError :message="form.errors.comment || form.errors.cart" />
                            </div>
                        </div>

                        <PrimaryButton :disabled="form.processing">
                            Подтвердить заказ
                        </PrimaryButton>
                    </form>
                </section>

                <aside class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Ваш заказ
                    </h2>

                    <div class="mt-6 space-y-4">
                        <div
                            v-for="item in cart.items"
                            :key="item.product_id"
                            class="flex items-start justify-between gap-4 border-b border-gray-100 pb-4"
                        >
                            <div>
                                <div class="font-medium text-gray-900">
                                    {{ item.name }}
                                </div>
                                <div class="mt-1 text-sm text-gray-500">
                                    {{ item.shop_name }} · {{ item.quantity }} шт.
                                </div>
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ formatPrice(item.line_total) }}
                            </div>
                        </div>
                    </div>

                    <dl class="mt-6 space-y-3 text-sm text-gray-600">
                        <div class="flex items-center justify-between gap-4">
                            <dt>Подытог</dt>
                            <dd class="font-medium text-gray-900">{{ formatPrice(cart.subtotal_amount) }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 border-t border-gray-200 pt-3 text-base">
                            <dt class="font-semibold text-gray-900">Итого</dt>
                            <dd class="font-semibold text-gray-900">{{ formatPrice(cart.total_amount) }}</dd>
                        </div>
                    </dl>
                </aside>
            </div>
        </div>
    </MarketLayout>
</template>
