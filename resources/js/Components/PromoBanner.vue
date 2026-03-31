<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    images: {
        type: Array,
        required: true,
    },
    autoplay: {
        type: Boolean,
        default: true,
    },
    interval: {
        type: Number,
        default: 5000,
    },
});

// Normalise slides: accept either string URLs or rich objects
const slides = computed(() => props.images.map((item, idx) => {
    if (typeof item === 'string') {
        return { type: 'image', src: item, id: idx };
    }
    return { type: 'gradient', id: idx, ...item };
}));

const currentIndex = ref(0);
let autoplayTimer = null;

const nextSlide = () => { currentIndex.value = (currentIndex.value + 1) % slides.value.length; };
const prevSlide = () => { currentIndex.value = currentIndex.value === 0 ? slides.value.length - 1 : currentIndex.value - 1; };

const goToSlide = (index) => {
    currentIndex.value = index;
    resetAutoplay();
};

const stopAutoplay = () => {
    if (autoplayTimer) {
        clearInterval(autoplayTimer);
        autoplayTimer = null;
    }
};

const startAutoplay = () => {
    if (props.autoplay && slides.value.length > 1) {
        autoplayTimer = setInterval(nextSlide, props.interval);
    }
};

const resetAutoplay = () => {
    stopAutoplay();
    startAutoplay();
};

onMounted(startAutoplay);
onUnmounted(stopAutoplay);
</script>

<template>
    <div
        v-if="slides.length > 0"
        class="relative w-full overflow-hidden rounded-2xl"
        @mouseenter="stopAutoplay"
        @mouseleave="startAutoplay"
    >
        <div class="relative aspect-[16/6] md:aspect-[21/6]">
            <TransitionGroup name="banner-fade">
                <div
                    v-for="(slide, index) in slides"
                    v-show="index === currentIndex"
                    :key="slide.id"
                    class="absolute inset-0"
                >
                    <!-- Image slide -->
                    <template v-if="slide.type === 'image'">
                        <img
                            :src="slide.src"
                            :alt="`Баннер ${index + 1}`"
                            class="h-full w-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-black/20 to-transparent" />
                    </template>

                    <!-- Gradient content slide -->
                    <template v-else>
                        <div
                            class="absolute inset-0 flex items-center overflow-hidden"
                            :style="{
                                background: slide.gradient
                                    ? `linear-gradient(135deg, ${slide.gradient.from} 0%, ${slide.gradient.to} 100%)`
                                    : 'linear-gradient(135deg, #6237F5 0%, #2563EB 100%)'
                            }"
                        >
                            <!-- Decorative bubbles -->
                            <div class="pointer-events-none absolute -right-20 -top-20 h-72 w-72 rounded-full bg-white/10" />
                            <div class="pointer-events-none absolute -bottom-28 right-28 h-56 w-56 rounded-full bg-white/[0.07]" />
                            <div class="pointer-events-none absolute right-1/3 top-1/4 h-36 w-36 rounded-full bg-white/10" />
                            <div class="pointer-events-none absolute left-2/3 bottom-0 h-24 w-24 rounded-full bg-white/[0.06]" />

                            <!-- Content -->
                            <div class="relative z-10 px-8 py-6 md:px-14 lg:px-16">
                                <span
                                    v-if="slide.badge"
                                    class="mb-3 inline-block rounded-full bg-white/25 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white backdrop-blur-sm"
                                >
                                    {{ slide.badge }}
                                </span>
                                <h2 class="text-2xl font-bold leading-tight text-white sm:text-3xl lg:text-4xl">
                                    {{ slide.title }}
                                </h2>
                                <p
                                    v-if="slide.subtitle"
                                    class="mt-2 max-w-md text-sm text-white/80 sm:text-base"
                                >
                                    {{ slide.subtitle }}
                                </p>
                                <Link
                                    v-if="slide.link && slide.cta"
                                    :href="slide.link"
                                    class="mt-4 inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-semibold shadow-lg transition hover:bg-gray-50 hover:shadow-xl"
                                    :style="{ color: slide.gradient?.from || '#6237F5' }"
                                >
                                    {{ slide.cta }}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </template>
                </div>
            </TransitionGroup>
        </div>

        <!-- Navigation arrows -->
        <template v-if="slides.length > 1">
            <button
                class="absolute left-3 top-1/2 z-10 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white/80 shadow-md backdrop-blur-sm transition hover:bg-white hover:scale-105"
                aria-label="Назад"
                @click="prevSlide(); resetAutoplay();"
            >
                <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button
                class="absolute right-3 top-1/2 z-10 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white/80 shadow-md backdrop-blur-sm transition hover:bg-white hover:scale-105"
                aria-label="Вперёд"
                @click="nextSlide(); resetAutoplay();"
            >
                <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Dot indicators -->
            <div class="absolute bottom-3 left-1/2 z-10 flex -translate-x-1/2 gap-1.5">
                <button
                    v-for="(_, index) in slides"
                    :key="index"
                    class="h-2 rounded-full bg-white transition-all duration-300"
                    :class="index === currentIndex ? 'w-6 opacity-100' : 'w-2 opacity-50 hover:opacity-75'"
                    :aria-label="`Слайд ${index + 1}`"
                    @click="goToSlide(index)"
                />
            </div>
        </template>
    </div>
</template>

<style scoped>
.banner-fade-enter-active,
.banner-fade-leave-active {
    transition: opacity 0.5s ease;
}

.banner-fade-enter-from,
.banner-fade-leave-to {
    opacity: 0;
}

.banner-fade-enter-to,
.banner-fade-leave-from {
    opacity: 1;
}
</style>
