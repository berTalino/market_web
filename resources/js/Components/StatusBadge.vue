<script setup>
import { computed } from 'vue';

const props = defineProps({
    value: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        default: null,
    },
});

const classes = computed(() => {
    const variants = {
        active: 'bg-emerald-100 text-emerald-700 ring-emerald-600/20',
        inactive: 'bg-gray-100 text-gray-700 ring-gray-500/20',
        pending: 'bg-amber-100 text-amber-700 ring-amber-600/20',
        confirmed: 'bg-sky-100 text-sky-700 ring-sky-600/20',
        completed: 'bg-emerald-100 text-emerald-700 ring-emerald-600/20',
        cancelled: 'bg-rose-100 text-rose-700 ring-rose-600/20',
        admin: 'bg-violet-100 text-violet-700 ring-violet-600/20',
        owner: 'bg-orange-100 text-orange-700 ring-orange-600/20',
    };

    return variants[props.value] ?? 'bg-gray-100 text-gray-700 ring-gray-500/20';
});

const text = computed(() => props.label ?? props.value);
</script>

<template>
    <span
        :class="classes"
        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold capitalize ring-1 ring-inset"
    >
        {{ text }}
    </span>
</template>
