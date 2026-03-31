<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: '',
    },
    image: {
        type: String,
        default: '',
    },
    color: {
        type: String,
        default: '#F4EBFF',
    },
    link: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="link"
        class="group block relative overflow-hidden rounded-lg transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
        :style="{ backgroundColor: color }"
    >
        <div class="flex flex-col items-center justify-center p-6 min-h-[140px] transition-colors duration-300">
            <!-- Иконка/Изображение категории -->
            <div v-if="image" class="mb-3 transition-transform duration-300 group-hover:scale-110">
                <img
                    :src="image"
                    :alt="title"
                    class="w-12 h-12 object-contain"
                />
            </div>
            <div v-else class="mb-3 text-4xl transition-transform duration-300 group-hover:scale-110">
                📦
            </div>

            <!-- Заголовок -->
            <h3 class="text-base font-semibold text-gray-900 text-center mb-1">
                {{ title }}
            </h3>

            <!-- Подзаголовок -->
            <p v-if="subtitle" class="text-xs text-gray-600 text-center">
                {{ subtitle }}
            </p>

            <!-- Индикатор hover -->
            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-5 transition-opacity duration-300" />
        </div>
    </Link>
</template>
