<script setup>
import MarketLayout from '@/Layouts/MarketLayout.vue';
</script>

<template>
    <MarketLayout>
        <template
            v-if="$slots.header"
            #header
        >
            <slot name="header" />
        </template>

        <slot />
    </MarketLayout>
</template>
