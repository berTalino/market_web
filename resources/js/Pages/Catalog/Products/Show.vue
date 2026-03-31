<script setup>
import FlashMessage from '@/Components/FlashMessage.vue';
import InputError from '@/Components/InputError.vue';
import MarketLayout from '@/Layouts/MarketLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';
import { formatPrice } from '@/utils/formatters';
import { getProductImage } from '@/utils/placeholders';

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

const imageError = ref(false);
const imageUrl = computed(() => getProductImage(props.product, 600, 600));

const rating = computed(() => {
    const seed = props.product.id || 1;
    const random = ((seed * 9301 + 49297) % 233280) / 233280;
    return Math.round((3.5 + random * 1.5) * 10) / 10;
});

const reviewCount = computed(() => {
    const seed = props.product.id || 1;
    return Math.floor(((seed * 9301 + 49297) % 233280) / 233280 * 500) + 10;
});

const stars = computed(() => {
    const full = Math.floor(rating.value);
    const half = rating.value % 1 >= 0.5;
    const empty = 5 - full - (half ? 1 : 0);
    return { full, half, empty };
});

const stockMeta = computed(() => {
    const s = props.product.stock;
    if (s >= 15) return { label: 'В наличии', note: `${s} шт.`, dotClass: 'bg-emerald-500', textClass: 'text-emerald-700', bgClass: 'bg-emerald-50 border-emerald-100' };
    if (s >= 5) return { label: 'Заканчивается', note: `${s} шт.`, dotClass: 'bg-amber-500', textClass: 'text-amber-700', bgClass: 'bg-amber-50 border-amber-100' };
    return { label: 'Мало', note: `${s} шт.`, dotClass: 'bg-rose-500', textClass: 'text-rose-700', bgClass: 'bg-rose-50 border-rose-100' };
});
</script>

<template>
    <Head :title="product.name" />

    <MarketLayout>
        <div class="section-container py-6">
            <!-- Breadcrumbs -->
            <nav class="mb-6 flex flex-wrap items-center gap-1.5 text-sm text-slate-500">
                <Link :href="route('welcome')" class="hover:text-slate-800 transition-colors">Главная</Link>
                <svg class="h-3.5 w-3.5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <Link :href="route('shops.index')" class="hover:text-slate-800 transition-colors">Магазины</Link>
                <svg class="h-3.5 w-3.5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <Link :href="route('shops.show', shop.slug)" class="hover:text-slate-800 transition-colors">{{ shop.name }}</Link>
                <svg class="h-3.5 w-3.5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="max-w-[200px] truncate text-slate-800">{{ product.name }}</span>
            </nav>

            <!-- Product main block -->
            <div class="grid gap-8 lg:grid-cols-[1fr,400px] lg:items-start xl:grid-cols-[1fr,440px]">

                <!-- Left: Product image -->
                <div class="surface-card !p-0 overflow-hidden">
                    <div class="relative aspect-square">
                        <img
                            :src="imageError ? getProductImage({ id: product.id, name: product.name }, 600, 600) : imageUrl"
                            :alt="product.name"
                            class="h-full w-full object-cover"
                            @error="imageError = true"
                        />
                    </div>
                </div>

                <!-- Right: Buy box -->
                <div class="space-y-5 lg:sticky lg:top-24">
                    <!-- Shop link -->
                    <Link
                        :href="route('shops.show', shop.slug)"
                        class="text-sm font-semibold text-brand-600 hover:text-brand-700 transition-colors"
                    >
                        {{ shop.name }}
                    </Link>

                    <!-- Product name -->
                    <h1 class="text-2xl font-bold leading-tight text-slate-900 sm:text-3xl">
                        {{ product.name }}
                    </h1>

                    <!-- Rating -->
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-0.5">
                            <svg
                                v-for="n in stars.full"
                                :key="`full-${n}`"
                                class="h-4 w-4 fill-amber-400"
                                viewBox="0 0 20 20"
                            >
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                            </svg>
                            <svg
                                v-if="stars.half"
                                class="h-4 w-4 text-amber-400"
                                viewBox="0 0 20 20"
                            >
                                <defs>
                                    <linearGradient id="half-grad">
                                        <stop offset="50%" stop-color="currentColor" />
                                        <stop offset="50%" stop-color="#E2E8F0" />
                                    </linearGradient>
                                </defs>
                                <path fill="url(#half-grad)" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                            </svg>
                            <svg
                                v-for="n in stars.empty"
                                :key="`empty-${n}`"
                                class="h-4 w-4 fill-slate-200"
                                viewBox="0 0 20 20"
                            >
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                            </svg>
                        </div>
                        <span class="text-sm text-slate-500">{{ rating }} <span class="text-slate-400">({{ reviewCount }} отзывов)</span></span>
                    </div>

                    <!-- Price -->
                    <div class="border-y border-slate-100 py-4">
                        <p class="text-4xl font-bold text-slate-900">{{ formatPrice(product.price) }}</p>
                    </div>

                    <!-- Stock status -->
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-sm font-semibold"
                            :class="[stockMeta.bgClass, stockMeta.textClass]"
                        >
                            <span class="h-2 w-2 rounded-full" :class="stockMeta.dotClass" />
                            {{ stockMeta.label }} — {{ stockMeta.note }}
                        </span>
                    </div>

                    <!-- Flash / errors -->
                    <FlashMessage />

                    <!-- Add to cart form -->
                    <form class="space-y-4" @submit.prevent="submit">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Количество</label>
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-xl font-bold text-slate-600 transition hover:border-brand-200 hover:bg-brand-50 hover:text-brand-700 disabled:cursor-not-allowed disabled:opacity-40"
                                    :disabled="form.quantity <= 1 || form.processing"
                                    @click="form.quantity = Math.max(1, Number(form.quantity) - 1)"
                                >
                                    −
                                </button>
                                <input
                                    v-model="form.quantity"
                                    type="number"
                                    min="1"
                                    :max="product.stock"
                                    class="w-16 rounded-xl border border-slate-200 text-center text-base font-semibold shadow-none focus:border-brand-300 focus:ring-brand-100"
                                />
                                <button
                                    type="button"
                                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-xl font-bold text-slate-600 transition hover:border-brand-200 hover:bg-brand-50 hover:text-brand-700 disabled:cursor-not-allowed disabled:opacity-40"
                                    :disabled="Number(form.quantity) >= product.stock || form.processing"
                                    @click="form.quantity = Math.min(product.stock, Number(form.quantity) + 1)"
                                >
                                    +
                                </button>
                            </div>
                            <InputError :message="form.errors.quantity || form.errors.product_id" class="mt-1" />
                        </div>

                        <button
                            type="submit"
                            class="btn-primary w-full py-3.5 text-base"
                            :disabled="form.processing || product.stock < 1"
                        >
                            {{ form.processing ? 'Добавляем…' : 'Добавить в корзину' }}
                        </button>
                    </form>

                    <div class="flex gap-3">
                        <Link :href="route('cart.index')" class="btn-secondary flex-1 justify-center">
                            Открыть корзину
                        </Link>
                        <Link :href="route('shops.show', shop.slug)" class="btn-ghost px-4">
                            В магазин
                        </Link>
                    </div>

                    <!-- Гарантии -->
                    <div class="space-y-2 rounded-xl border border-slate-100 bg-slate-50 p-4 text-sm text-slate-600">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 flex-none text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Безопасная оплата
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 flex-none text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Проверенный продавец
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 flex-none text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Актуальные остатки
                        </div>
                    </div>
                </div>
            </div>

            <!-- Описание -->
            <div class="mt-8 elevated-card">
                <h2 class="mb-3 text-lg font-bold text-slate-900">Описание</h2>
                <p class="text-sm leading-7 text-slate-600">
                    {{ product.description || 'Описание товара пока не добавлено.' }}
                </p>
            </div>

            <!-- Характеристики -->
            <div class="mt-4 elevated-card">
                <h2 class="mb-4 text-lg font-bold text-slate-900">Характеристики</h2>
                <dl class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 px-4 py-3">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Цена</dt>
                        <dd class="mt-1 font-semibold text-slate-900">{{ formatPrice(product.price) }}</dd>
                    </div>
                    <div class="rounded-xl bg-slate-50 px-4 py-3">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Остаток</dt>
                        <dd class="mt-1 font-semibold text-slate-900">{{ product.stock }} шт.</dd>
                    </div>
                    <div class="rounded-xl bg-slate-50 px-4 py-3">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Магазин</dt>
                        <dd class="mt-1 font-semibold text-slate-900">{{ shop.name }}</dd>
                    </div>
                    <div class="rounded-xl bg-slate-50 px-4 py-3">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Рейтинг</dt>
                        <dd class="mt-1 font-semibold text-slate-900">{{ rating }} из 5 ({{ reviewCount }} отзывов)</dd>
                    </div>
                </dl>
            </div>
        </div>
    </MarketLayout>
</template>
