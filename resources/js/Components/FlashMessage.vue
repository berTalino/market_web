<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    message: {
        type: String,
        default: null,
    },
});

const page = usePage();

const resolvedMessage = computed(() => props.message ?? page.props.flash?.success ?? null);
</script>

<template>
    <div
        v-if="resolvedMessage"
        class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm"
    >
        {{ resolvedMessage }}
    </div>
</template>
