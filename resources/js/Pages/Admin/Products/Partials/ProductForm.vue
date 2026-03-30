<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    product: {
        type: Object,
        default: null,
    },
    shops: {
        type: Array,
        default: () => [],
    },
    defaults: {
        type: Object,
        default: () => ({}),
    },
    submitUrl: {
        type: String,
        required: true,
    },
    submitMethod: {
        type: String,
        default: 'post',
    },
    submitLabel: {
        type: String,
        required: true,
    },
});

const source = props.product ?? props.defaults;

const form = useForm({
    shop_id: source.shop_id ?? '',
    name: source.name ?? '',
    slug: source.slug ?? '',
    description: source.description ?? '',
    price: source.price ?? 0,
    stock: source.stock ?? 0,
    is_active: Boolean(source.is_active ?? true),
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        shop_id: Number(data.shop_id),
        description: data.description ? data.description : null,
        price: Number(data.price),
        stock: Number(data.stock),
        is_active: data.is_active ? 1 : 0,
    }))[props.submitMethod](props.submitUrl, {
        preserveScroll: true,
    });
};
</script>

<template>
    <form
        class="space-y-8 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm"
        @submit.prevent="submit"
    >
        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2 md:col-span-2">
                <label class="text-sm font-medium text-gray-700">
                    Магазин
                </label>
                <select
                    v-model="form.shop_id"
                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">
                        Выберите магазин
                    </option>
                    <option
                        v-for="shop in shops"
                        :key="shop.id"
                        :value="shop.id"
                    >
                        {{ shop.name }} · {{ shop.slug }}
                    </option>
                </select>
                <InputError :message="form.errors.shop_id" />
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="text-sm font-medium text-gray-700">
                    Название товара
                </label>
                <input
                    v-model="form.name"
                    type="text"
                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Например, Органический мёд"
                >
                <InputError :message="form.errors.name" />
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Slug
                </label>
                <input
                    v-model="form.slug"
                    type="text"
                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="organic-honey"
                >
                <InputError :message="form.errors.slug" />
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Цена, ₽
                </label>
                <input
                    v-model="form.price"
                    type="number"
                    min="1"
                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                <InputError :message="form.errors.price" />
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Остаток
                </label>
                <input
                    v-model="form.stock"
                    type="number"
                    min="0"
                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                <InputError :message="form.errors.stock" />
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="text-sm font-medium text-gray-700">
                    Описание
                </label>
                <textarea
                    v-model="form.description"
                    rows="5"
                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Опишите товар, ключевые свойства и особенности."
                />
                <InputError :message="form.errors.description" />
            </div>
        </div>

        <label class="flex items-start gap-3 rounded-2xl border border-gray-200 bg-gray-50 p-4">
            <input
                v-model="form.is_active"
                type="checkbox"
                class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
            >
            <span>
                <span class="block text-sm font-medium text-gray-900">
                    Товар активен
                </span>
                <span class="mt-1 block text-sm text-gray-500">
                    Активный товар можно показывать в публичном каталоге при наличии остатка.
                </span>
            </span>
        </label>
        <InputError :message="form.errors.is_active" />

        <div class="flex flex-col gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-500">
                Цена и остаток сохраняются как значения MVP без дополнительных настроек.
            </p>

            <div class="flex flex-wrap items-center gap-3">
                <Link :href="route('admin.products.index')">
                    <SecondaryButton>
                        Отмена
                    </SecondaryButton>
                </Link>
                <PrimaryButton :disabled="form.processing">
                    {{ submitLabel }}
                </PrimaryButton>
            </div>
        </div>
    </form>
</template>
