<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900">
            {{ title }}
        </h3>
        <p
            v-if="description"
            class="mx-auto mt-2 max-w-2xl text-sm leading-6 text-gray-500"
        >
            {{ description }}
        </p>
        <div
            v-if="$slots.default"
            class="mt-6"
        >
            <slot />
        </div>
    </div>
</template>
