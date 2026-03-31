<script setup>
import InputError from '@/Components/InputError.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, ref } from 'vue';

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
    image: null,
});

const imageInput = ref(null);
const uploadedImagePreview = ref(null);
let uploadedImageObjectUrl = null;

const revokeUploadedImagePreview = () => {
    if (uploadedImageObjectUrl) {
        URL.revokeObjectURL(uploadedImageObjectUrl);
        uploadedImageObjectUrl = null;
    }
};

const updateUploadedImagePreview = (file) => {
    revokeUploadedImagePreview();

    if (!file) {
        uploadedImagePreview.value = null;
        return;
    }

    uploadedImageObjectUrl = URL.createObjectURL(file);
    uploadedImagePreview.value = uploadedImageObjectUrl;
};

const handleImageChange = (event) => {
    const [file] = event.target.files ?? [];
    form.image = file ?? null;
    updateUploadedImagePreview(file ?? null);
};

const clearSelectedImage = () => {
    form.image = null;
    updateUploadedImagePreview(null);

    if (imageInput.value) {
        imageInput.value.value = '';
    }
};

const productImageUrl = computed(() => uploadedImagePreview.value || props.product?.image_url || null);

const imagePreviewLabel = computed(() => {
    if (form.image) {
        return 'Новое изображение выбрано';
    }

    if (props.product?.image_url) {
        return 'Текущее изображение товара';
    }

    return 'Изображение пока не добавлено';
});

const imageFallbackLetter = computed(() => {
    const value = String(form.name || source.name || 'Т').trim();

    return value.charAt(0).toUpperCase() || 'Т';
});

onBeforeUnmount(() => {
    revokeUploadedImagePreview();
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
        forceFormData: true,
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
                        Основные параметры товара
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Заполните базовую информацию о карточке, публикации и стоимости товара.
                    </p>
                </div>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-medium text-slate-700">
                        Магазин
                    </label>
                    <div class="field-shell">
                        <select v-model="form.shop_id">
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
                    </div>
                    <InputError :message="form.errors.shop_id" />
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-medium text-slate-700">
                        Название товара
                    </label>
                    <div class="field-shell">
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Например, Органический мёд"
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
                            placeholder="organic-honey"
                        >
                    </div>
                    <InputError :message="form.errors.slug" />
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700">
                        Цена, ₽
                    </label>
                    <div class="field-shell">
                        <input
                            v-model="form.price"
                            type="number"
                            min="1"
                        >
                    </div>
                    <InputError :message="form.errors.price" />
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-medium text-slate-700">
                        Остаток
                    </label>
                    <div class="field-shell">
                        <input
                            v-model="form.stock"
                            type="number"
                            min="0"
                        >
                    </div>
                    <InputError :message="form.errors.stock" />
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-medium text-slate-700">
                        Описание
                    </label>
                    <div class="field-shell items-start">
                        <textarea
                            v-model="form.description"
                            rows="6"
                            placeholder="Опишите товар, ключевые свойства и особенности."
                        ></textarea>
                    </div>
                    <InputError :message="form.errors.description" />
                </div>

                <div class="md:col-span-2">
                    <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-slate-50/80">
                        <div class="grid gap-0 lg:grid-cols-[0.92fr,1.08fr]">
                            <div class="border-b border-slate-200 bg-white/70 p-5 lg:border-b-0 lg:border-r">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">
                                            Preview
                                        </p>
                                        <p class="mt-2 text-sm font-medium text-slate-700">
                                            {{ imagePreviewLabel }}
                                        </p>
                                    </div>

                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                        1:1 / 4:5
                                    </span>
                                </div>

                                <div class="relative mt-5 aspect-[4/5] overflow-hidden rounded-[1.5rem] bg-slate-950 shadow-lg shadow-slate-900/10">
                                    <img
                                        v-if="productImageUrl"
                                        :src="productImageUrl"
                                        alt="Предпросмотр изображения товара"
                                        class="h-full w-full object-cover"
                                    >
                                    <div
                                        v-else
                                        class="absolute inset-0 flex flex-col items-center justify-center bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.22),transparent_32%),linear-gradient(145deg,#0f172a,#334155_55%,#475569)] px-6 text-center text-white"
                                    >
                                        <div class="flex h-20 w-20 items-center justify-center rounded-[1.75rem] border border-white/15 bg-white/10 text-3xl font-semibold shadow-lg shadow-black/10 backdrop-blur">
                                            {{ imageFallbackLetter }}
                                        </div>
                                        <p class="mt-5 text-xs font-semibold uppercase tracking-[0.28em] text-white/65">
                                            Placeholder preview
                                        </p>
                                        <p class="mt-3 max-w-xs text-sm leading-6 text-white/85">
                                            Здесь появится фотография товара после загрузки. Пока показываем аккуратную заглушку без битого изображения.
                                        </p>
                                    </div>
                                    <div class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent px-5 pb-5 pt-12">
                                        <p class="text-sm font-semibold text-white">
                                            {{ form.name || 'Название товара' }}
                                        </p>
                                        <p class="mt-1 text-xs text-white/70">
                                            {{ productImageUrl ? 'Фото будет видно в каталоге и на странице товара.' : 'Загрузите фото, чтобы карточка выглядела заметно привлекательнее.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4 p-5 sm:p-6">
                                <div>
                                    <label class="text-sm font-medium text-slate-700">
                                        Изображение товара
                                    </label>
                                    <p class="mt-2 text-sm leading-6 text-slate-500">
                                        Поддерживаются JPG, PNG, WEBP и GIF. Лучше использовать светлое, чёткое фото товара без лишнего фона.
                                    </p>
                                </div>

                                <label class="flex cursor-pointer flex-col items-center justify-center rounded-[1.5rem] border border-dashed border-slate-300 bg-white px-6 py-8 text-center transition hover:border-brand-300 hover:bg-brand-50/40">
                                    <input
                                        ref="imageInput"
                                        type="file"
                                        accept="image/png,image/jpeg,image/webp,image/gif"
                                        class="sr-only"
                                        @change="handleImageChange"
                                    >
                                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-xl text-brand-600">
                                        ↑
                                    </span>
                                    <span class="mt-4 text-sm font-semibold text-slate-900">
                                        {{ form.image ? 'Выбрать другое изображение' : 'Загрузить изображение' }}
                                    </span>
                                    <span class="mt-2 max-w-md text-sm leading-6 text-slate-500">
                                        Файл отправится вместе с формой через Inertia upload и сразу будет использован в storefront-карточках.
                                    </span>
                                </label>

                                <div class="flex flex-wrap items-center gap-3 text-sm">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">
                                        {{ form.image ? form.image.name : 'Файл не выбран' }}
                                    </span>
                                    <button
                                        v-if="form.image"
                                        type="button"
                                        class="font-medium text-slate-500 transition hover:text-slate-800"
                                        @click="clearSelectedImage"
                                    >
                                        Сбросить новый файл
                                    </button>
                                </div>

                                <div class="rounded-[1.5rem] bg-slate-100/80 p-4 text-sm leading-6 text-slate-600">
                                    Если у товара уже есть {{ props.product?.image_url ? 'текущее' : 'будущее' }} изображение, оно отображается в превью. При выборе нового файла превью обновляется локально ещё до сохранения.
                                </div>

                                <InputError :message="form.errors.image" />
                            </div>
                        </div>
                    </div>
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
                        Активный товар можно показывать в публичном каталоге при наличии остатка. Цена и остаток сохраняются как значения MVP без дополнительных настроек.
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
                            Товар активен
                        </span>
                        <span class="mt-1 block text-sm leading-6 text-slate-500">
                            Если выключить переключатель, товар останется в базе, но скроется из витрины.
                        </span>
                    </span>
                </label>
            </div>
            <InputError :message="form.errors.is_active" class="mt-3" />
        </section>

        <section class="surface-card flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm leading-6 text-slate-500">
                Сохранение использует существующие create/update-маршруты и не затрагивает серверную логику.
            </p>

            <div class="flex flex-wrap items-center gap-3">
                <Link
                    :href="route('admin.products.index')"
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
