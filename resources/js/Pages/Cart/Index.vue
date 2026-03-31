<script setup>
import CartItemRow from '@/Components/CartItemRow.vue';
import MarketLayout from '@/Layouts/MarketLayout.vue';
import { formatPrice } from '@/utils/formatters';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { computed } from 'vue';

const props = defineProps({
    cart: {
        type: Object,
        required: true,
    },
});

function pluralRu(n, one, few, many) {
    const mod10 = Math.abs(n) % 10;
    const mod100 = Math.abs(n) % 100;
    if (mod100 >= 11 && mod100 <= 19) return many;
    if (mod10 === 1) return one;
    if (mod10 >= 2 && mod10 <= 4) return few;
    return many;
}

const itemsLabel = computed(() => `${props.cart.count} ${pluralRu(props.cart.count, 'товар', 'товара', 'товаров')}`);
</script>

<template>
    <Head title="Корзина — Platio Market" />

    <MarketLayout>
        <div class="section-container py-6 pb-12">

            <!-- Page header -->
            <div class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Корзина</h1>
                    <p v-if="!cart.is_empty" class="mt-1 text-sm text-slate-500">{{ itemsLabel }}</p>
                </div>
                <Link :href="route('shops.index')" class="btn-ghost hidden sm:inline-flex">
                    Продолжить покупки
                </Link>
            </div>

            <!-- Empty cart -->
            <div
                v-if="cart.is_empty"
                class="elevated-card flex flex-col items-center py-16 text-center"
            >
                <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">
                    <svg class="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-slate-900">Корзина пуста</h2>
                <p class="mt-2 max-w-sm text-sm text-slate-500">
                    Добавьте товары из каталога, чтобы оформить заказ
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <Link :href="route('shops.index')" class="btn-primary">
                        Перейти в каталог
                    </Link>
                    <Link :href="route('welcome')" class="btn-secondary">
                        На главную
                    </Link>
                </div>
            </div>

            <!-- Cart content -->
            <div
                v-else
                class="grid gap-6 lg:grid-cols-[minmax(0,1.5fr),360px] xl:grid-cols-[minmax(0,1.5fr),400px]"
            >
                <!-- Items list -->
                <section class="space-y-4">
                    <CartItemRow
                        v-for="item in cart.items"
                        :key="item.product_id"
                        :item="item"
                    />

                    <div class="pt-2">
                        <Link :href="route('shops.index')" class="btn-ghost sm:hidden">
                            Продолжить покупки
                        </Link>
                    </div>
                </section>

                <!-- Order summary -->
                <aside class="lg:sticky lg:top-24 lg:self-start">
                    <div class="elevated-card space-y-5">
                        <h2 class="text-lg font-bold text-slate-900">Итого</h2>

                        <dl class="space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-slate-600">Товары ({{ cart.count }} шт.)</dt>
                                <dd class="font-semibold text-slate-900">{{ formatPrice(cart.subtotal_amount) }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-slate-600">Доставка</dt>
                                <dd class="font-semibold text-emerald-600">Бесплатно</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 border-t border-slate-100 pt-3 text-base font-bold text-slate-900">
                                <dt>К оплате</dt>
                                <dd>{{ formatPrice(cart.total_amount) }}</dd>
                            </div>
                        </dl>

                        <Link :href="route('checkout.create')" class="btn-primary w-full py-3.5 text-base">
                            Оформить заказ
                        </Link>

                        <!-- Trust signals -->
                        <div class="space-y-2 rounded-xl border border-slate-100 bg-slate-50 p-4 text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 flex-none text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Проверенные продавцы
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 flex-none text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Прозрачная итоговая сумма
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 flex-none text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Безопасный checkout
                            </div>
                        </div>

                        <p class="text-center text-xs text-slate-400">
                            Данные защищены SSL-шифрованием
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </MarketLayout>
</template>
