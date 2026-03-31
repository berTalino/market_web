<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { getProductImage } from '@/utils/placeholders';
import { formatPrice } from '@/utils/formatters';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    showShopName: {
        type: Boolean,
        default: false,
    },
    compact: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['add-to-cart']);

const isFavorite = ref(false);
const imageError = ref(false);

const rating = computed(() => {
    const seed = props.product.id || 1;
    const min = 3.5;
    const max = 5.0;
    const random = ((seed * 9301 + 49297) % 233280) / 233280;
    return Math.round((min + random * (max - min)) * 10) / 10;
});

const reviewCount = computed(() => {
    const seed = props.product.id || 1;
    return Math.floor(((seed * 9301 + 49297) % 233280) / 233280 * 500) + 10;
});

const discountPercent = computed(() => {
    if (!props.product.compare_price || props.product.compare_price <= props.product.price) {
        return null;
    }
    const discount = Math.round(((props.product.compare_price - props.product.price) / props.product.compare_price) * 100);
    return discount > 0 ? discount : null;
});

const imageUrl = computed(() => getProductImage(props.product, 400, 400));

const fallbackLetter = computed(() => String(props.product.name || 'Т').trim().charAt(0).toUpperCase());

const productUrl = computed(() => {
    if (!props.product.shop) return '#';
    return route('products.show', {
        shop: props.product.shop.slug,
        product: props.product.slug,
    });
});

const toggleFavorite = (e) => {
    e.preventDefault();
    e.stopPropagation();
    isFavorite.value = !isFavorite.value;
};

const addToCart = (e) => {
    e.preventDefault();
    e.stopPropagation();
    emit('add-to-cart', props.product);
};

const stars = computed(() => {
    const fullStars = Math.floor(rating.value);
    const hasHalfStar = rating.value % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    return { full: fullStars, half: hasHalfStar, empty: emptyStars };
});
</script>

<template>
    <Link
        :href="productUrl"
        class="group block overflow-hidden rounded-2xl bg-white shadow-soft border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-surface hover:border-brand-100"
    >
        <!-- Image container -->
        <div class="relative aspect-square overflow-hidden bg-slate-50">
            <!-- Favorite button -->
            <button
                @click="toggleFavorite"
                class="absolute right-2 top-2 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-soft transition-all duration-200 hover:scale-110"
                :class="{ 'text-rose-500': isFavorite, 'text-slate-300': !isFavorite }"
            >
                <svg
                    class="h-4 w-4 transition-transform"
                    :class="{ 'fill-current': isFavorite }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                    />
                </svg>
            </button>

            <!-- Discount badge -->
            <div
                v-if="discountPercent"
                class="absolute left-2 top-2 z-10 rounded-lg bg-rose-500 px-2 py-1 text-xs font-bold text-white"
            >
                −{{ discountPercent }}%
            </div>

            <!-- Product image -->
            <img
                v-if="!imageError"
                :src="imageUrl"
                :alt="product.name"
                class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                loading="lazy"
                @error="imageError = true"
            />

            <!-- Fallback letter avatar -->
            <div
                v-else
                class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-brand-100 to-violet-100"
            >
                <span class="text-5xl font-bold text-brand-400">{{ fallbackLetter }}</span>
            </div>

            <!-- Add to cart overlay on hover -->
            <div class="absolute inset-x-0 bottom-0 translate-y-full bg-gradient-to-t from-brand-600/90 to-transparent p-3 transition-transform duration-300 group-hover:translate-y-0">
                <button
                    @click="addToCart"
                    class="w-full rounded-full bg-white py-2 text-xs font-bold text-brand-700 shadow-lg transition hover:bg-brand-50"
                >
                    В корзину
                </button>
            </div>
        </div>

        <!-- Card body -->
        <div class="space-y-2 p-3">
            <!-- Product name -->
            <h3 class="line-clamp-2 min-h-[2.5rem] text-sm font-medium leading-snug text-slate-900">
                {{ product.name }}
            </h3>

            <!-- Rating -->
            <div class="flex items-center gap-1.5 text-xs">
                <div class="flex items-center">
                    <svg v-for="n in stars.full" :key="`full-${n}`" class="h-3.5 w-3.5 fill-current text-amber-400" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                    </svg>
                    <svg v-if="stars.half" class="h-3.5 w-3.5 text-amber-400" viewBox="0 0 20 20">
                        <defs>
                            <linearGradient id="half-star-grad">
                                <stop offset="50%" stop-color="currentColor" />
                                <stop offset="50%" stop-color="#D1D5DB" />
                            </linearGradient>
                        </defs>
                        <path fill="url(#half-star-grad)" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                    </svg>
                    <svg v-for="n in stars.empty" :key="`empty-${n}`" class="h-3.5 w-3.5 fill-current text-slate-200" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                    </svg>
                </div>
                <span class="text-slate-400">({{ reviewCount }})</span>
            </div>

            <!-- Price -->
            <div class="flex items-baseline gap-2">
                <span class="text-lg font-bold text-slate-900">{{ formatPrice(product.price) }}</span>
                <span v-if="discountPercent" class="text-xs text-slate-400 line-through">
                    {{ formatPrice(product.compare_price) }}
                </span>
            </div>

            <!-- Shop name -->
            <div v-if="showShopName && product.shop" class="text-xs text-slate-400">
                {{ product.shop.name }}
            </div>
        </div>
    </Link>
</template>
