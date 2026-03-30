<script setup>
import EmptyState from '@/Components/EmptyState.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import MarketLayout from '@/Layouts/MarketLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { formatPrice } from '@/utils/formatters';

const props = defineProps({
    shop: {
        type: Object,
        required: true,
    },
    product: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    product_id: props.product.id,
    quantity: 1,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        quantity: Number(data.quantity),
    })).post(route('cart.items.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="product.name" />

    <MarketLayout>
        <template #header>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-medium uppercase tracking-[0.25em] text-indigo-600">
                        {{ shop.name }}
                    </p>
                    <h1 class="mt-2 text-3xl font-semibold text-gray-900">
                        {{ product.name }}
                    </h1>
                    <p class="mt-3 max-w-3xl text-base leading-7 text-gray-600">
                        {{ product.description || 'Описание товара пока отсутствует.' }}
                    </p>
                </div>

                <Link
                    :href="route('shops.show', shop.slug)"
                    class="inline-flex items-center text-sm font-medium text-indigo-600 transition hover:text-indigo-700"
                >
                    ← Назад в магазин
                </Link>
            </div>
        </template>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[1.2fr,0.8fr]">
                <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <p class="text-sm text-gray-500">Цена</p>
                            <p class="mt-2 text-3xl font-semibold text-gray-900">
                                {{ formatPrice(product.price) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Доступный остаток</p>
                            <p class="mt-2 text-3xl font-semibold text-gray-900">
                                {{ product.stock }} шт.
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 rounded-2xl border border-gray-100 bg-gray-50 p-5 text-sm leading-6 text-gray-600">
                        Товар доступен для добавления в корзину и оформления через существующий checkout flow.
                    </div>
                </section>

                <aside class="space-y-4 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Добавить в корзину
                    </h2>

                    <FlashMessage />

                    <form
                        class="space-y-4"
                        @submit.prevent="submit"
                    >
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Количество
                            </label>
                            <input
                                v-model="form.quantity"
                                type="number"
                                min="1"
                                :max="product.stock"
                                class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <InputError
                                :message="form.errors.quantity || form.errors.product_id"
                                class="mt-2"
                            />
                        </div>

                        <PrimaryButton :disabled="form.processing">
                            Добавить в корзину
                        </PrimaryButton>
                    </form>

                    <div class="border-t border-gray-200 pt-4 text-sm text-gray-500">
                        После добавления можно перейти в корзину, изменить количество и оформить заказ.
                    </div>

                    <Link
                        :href="route('cart.index')"
                        class="inline-flex text-sm font-medium text-indigo-600 transition hover:text-indigo-700"
                    >
                        Открыть корзину →
                    </Link>
                </aside>
            </div>
        </div>
    </MarketLayout>
</template>
