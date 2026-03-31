<script setup>
import InputError from '@/Components/InputError.vue';
import { formatPrice } from '@/utils/formatters';
import { router, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { computed } from 'vue';

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    quantity: props.item.quantity,
});

const quantityLimit = computed(() => Math.max(1, props.item.stock));

const clampQuantity = (value) => Math.min(quantityLimit.value, Math.max(1, Number(value) || 1));

const submit = () => {
    form.transform((data) => ({
        quantity: clampQuantity(data.quantity),
    })).patch(route('cart.items.update', props.item.product_id), {
        preserveScroll: true,
    });
};

const adjustQuantity = (delta) => {
    form.quantity = clampQuantity(Number(form.quantity) + delta);
    submit();
};

const remove = () => {
    router.delete(route('cart.items.destroy', props.item.product_id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <article class="elevated-card">
        <div class="flex items-start gap-4">
            <!-- Product placeholder image -->
            <div class="h-20 w-20 flex-none overflow-hidden rounded-xl bg-gradient-to-br from-brand-100 to-violet-100 flex items-center justify-center sm:h-24 sm:w-24">
                <span class="text-2xl font-bold text-brand-400 sm:text-3xl">
                    {{ item.name.charAt(0).toUpperCase() }}
                </span>
            </div>

            <!-- Info + controls -->
            <div class="min-w-0 flex-1 space-y-3">
                <!-- Top row: name + remove -->
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-brand-600">{{ item.shop_name }}</p>
                        <h3 class="mt-0.5 font-semibold leading-snug text-slate-900 line-clamp-2">{{ item.name }}</h3>
                        <p class="mt-1 text-xs text-slate-400">{{ item.stock }} шт. в наличии</p>
                    </div>
                    <button
                        class="flex-none rounded-full p-1.5 text-slate-400 transition hover:bg-rose-50 hover:text-rose-500 disabled:opacity-40"
                        :disabled="form.processing"
                        @click="remove"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Bottom row: quantity + price -->
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <!-- Quantity control -->
                    <div class="flex items-center gap-1 rounded-xl border border-slate-200 bg-slate-50 p-1">
                        <button
                            type="button"
                            class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-600 transition hover:bg-white hover:text-brand-700 disabled:cursor-not-allowed disabled:opacity-40"
                            :disabled="form.processing || Number(form.quantity) <= 1"
                            @click="adjustQuantity(-1)"
                        >
                            −
                        </button>
                        <span class="w-8 text-center text-sm font-semibold text-slate-900">{{ form.quantity }}</span>
                        <button
                            type="button"
                            class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-600 transition hover:bg-white hover:text-brand-700 disabled:cursor-not-allowed disabled:opacity-40"
                            :disabled="form.processing || Number(form.quantity) >= quantityLimit"
                            @click="adjustQuantity(1)"
                        >
                            +
                        </button>
                    </div>

                    <!-- Price -->
                    <div class="text-right">
                        <p class="text-xs text-slate-400">{{ formatPrice(item.price) }} × {{ form.quantity }}</p>
                        <p class="text-lg font-bold text-slate-900">{{ formatPrice(item.line_total) }}</p>
                    </div>
                </div>

                <InputError :message="form.errors.quantity" class="text-xs" />
            </div>
        </div>
    </article>
</template>
