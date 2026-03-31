<script setup>
import InputError from '@/Components/InputError.vue';
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
    <form class="space-y-6" @submit.prevent="submit">
        <section class="surface-card">
            <div class="flex flex-col gap-3 border-b border-slate-100 pb-5">
                <p class="section-eyebrow">
                    Core data
                </p>
                <div>
                    <h2 class="text-2xl font-semibold text-slate-950">
                        Основные данные магазина
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Настройте имя, slug, владельца и описание магазина в едином административном паттерне.
                    </p>
                </div>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-medium text-slate-700">
                        Название магазина
                    </label>
                    <div class="field-shell">
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Например, Fresh Market"
                        >
                    </div>
                    <InputError :message="form.errors.name" />
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700">
                        Slug
                    </label>
                    <div class="field-shell">
                        <input
                            v-model="form.slug"
                            type="text"
                            placeholder="fresh-market"
                        >
                    </div>
                    <InputError :message="form.errors.slug" />
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700">
                        Владелец
                    </label>
                    <div class="field-shell">
                        <select v-model="form.owner_id">
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
                    </div>
                    <InputError :message="form.errors.owner_id" />
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-medium text-slate-700">
                        Описание
                    </label>
                    <div class="field-shell items-start">
                        <textarea
                            v-model="form.description"
                            rows="6"
                            placeholder="Коротко опишите магазин и ассортимент."
                        ></textarea>
                    </div>
                    <InputError :message="form.errors.description" />
                </div>
            </div>
        </section>

        <section class="elevated-card">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div class="max-w-2xl">
                    <p class="section-eyebrow">
                        Availability
                    </p>
                    <h2 class="mt-3 text-2xl font-semibold text-slate-950">
                        Статус публикации
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Активные магазины отображаются в публичном каталоге. Изменения сохраняются через существующий backend CRUD и маршруты остаются без изменений.
                    </p>
                </div>

                <label class="flex w-full max-w-md items-start gap-3 rounded-brand-lg border border-slate-200 bg-slate-50/80 p-4 lg:w-auto">
                    <input
                        v-model="form.is_active"
                        type="checkbox"
                        class="mt-1"
                    >
                    <span>
                        <span class="block text-sm font-medium text-slate-900">
                            Магазин активен
                        </span>
                        <span class="mt-1 block text-sm leading-6 text-slate-500">
                            Отключение статуса скрывает магазин из каталога, но не меняет данные объекта.
                        </span>
                    </span>
                </label>
            </div>
            <InputError :message="form.errors.is_active" class="mt-3" />
        </section>

        <section class="surface-card flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm leading-6 text-slate-500">
                Форма сохраняет совместимость с существующим create/update-flow и текущими данными магазинов.
            </p>

            <div class="flex flex-wrap items-center gap-3">
                <Link
                    :href="route('admin.shops.index')"
                    class="btn-secondary"
                >
                    Отмена
                </Link>
                <button
                    type="submit"
                    class="btn-primary"
                    :disabled="form.processing"
                >
                    {{ submitLabel }}
                </button>
            </div>
        </section>
    </form>
</template>
