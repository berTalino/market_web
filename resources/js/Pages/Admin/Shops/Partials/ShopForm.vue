<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    shop: {
        type: Object,
        default: null,
    },
    owners: {
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

const source = props.shop ?? props.defaults;

const form = useForm({
    name: source.name ?? '',
    slug: source.slug ?? '',
    description: source.description ?? '',
    owner_id: source.owner_id ?? '',
    is_active: Boolean(source.is_active ?? true),
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        description: data.description ? data.description : null,
        owner_id: data.owner_id === '' ? null : Number(data.owner_id),
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
                    Название магазина
                </label>
                <input
                    v-model="form.name"
                    type="text"
                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Например, Fresh Market"
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
                    placeholder="fresh-market"
                >
                <InputError :message="form.errors.slug" />
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Владелец
                </label>
                <select
                    v-model="form.owner_id"
                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">
                        Не назначен
                    </option>
                    <option
                        v-for="owner in owners"
                        :key="owner.id"
                        :value="owner.id"
                    >
                        {{ owner.name }} · {{ owner.email }}
                    </option>
                </select>
                <InputError :message="form.errors.owner_id" />
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="text-sm font-medium text-gray-700">
                    Описание
                </label>
                <textarea
                    v-model="form.description"
                    rows="5"
                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Коротко опишите магазин и ассортимент."
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
                    Магазин активен
                </span>
                <span class="mt-1 block text-sm text-gray-500">
                    Активные магазины отображаются в публичном каталоге.
                </span>
            </span>
        </label>
        <InputError :message="form.errors.is_active" />

        <div class="flex flex-col gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-500">
                Изменения сохраняются через существующий backend CRUD.
            </p>

            <div class="flex flex-wrap items-center gap-3">
                <Link :href="route('admin.shops.index')">
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
