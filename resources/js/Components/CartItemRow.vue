<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { computed } from 'vue';
import { formatPrice } from '@/utils/formatters';

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

const submit = () => {
    form.transform((data) => ({
        quantity: Number(data.quantity),
    })).patch(route('cart.items.update', props.item.product_id), {
        preserveScroll: true,
    });
};

const remove = () => {
    router.delete(route('cart.items.destroy', props.item.product_id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div class="space-y-2">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">
                    {{ item.shop_name }}
                </div>
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ item.name }}
                </h3>
                <p class="text-sm text-gray-500">
                    Цена за единицу: {{ formatPrice(item.price) }}
                </p>
                <p class="text-sm text-gray-500">
                    Доступно: {{ item.stock }} шт.
                </p>
            </div>

            <div class="w-full max-w-md space-y-4 lg:text-right">
                <div class="text-lg font-semibold text-gray-900">
                    {{ formatPrice(item.line_total) }}
                </div>

                <form
                    class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-end"
                    @submit.prevent="submit"
                >
                    <div class="sm:max-w-28">
                        <input
                            v-model="form.quantity"
                            type="number"
                            min="1"
                            :max="quantityLimit"
                            class="block w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        <InputError
                            :message="form.errors.quantity"
                            class="mt-2"
                        />
                    </div>

                    <PrimaryButton :disabled="form.processing">
                        Обновить
                    </PrimaryButton>

                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-md border border-rose-200 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-rose-600 transition hover:bg-rose-50"
                        @click="remove"
                    >
                        Удалить
                    </button>
                </form>

                <Link
                    :href="route('checkout.create')"
                    class="inline-flex text-sm font-medium text-indigo-600 transition hover:text-indigo-700"
                >
                    Перейти к оформлению →
                </Link>
            </div>
        </div>
    </div>
</template>
