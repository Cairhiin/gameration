<script setup>
import { computed } from 'vue';

const { variant, size } = defineProps({
    variant: {
        type: String,
        default: 'dark'
    },
    size: {
        type: String,
        default: 'normal'
    }
});

const style = computed(() => {
    let styles = '';

    if (variant === 'dark') {
        styles += ' bg-dark-highlight-variant text-dark';
    }

    if (variant === 'light') {
        styles += ' bg-light text-dark';
    }

    if (size === 'small') {
        styles += ' text-sm';
    }

    if (size === 'normal') {
        styles += ' text-base';
    }

    if (size === 'large') {
        styles += ' text-lg';
    }

    return styles;
});
</script>

<template>
    <span class="rounded-3xl px-4 py-1 font-bold" :class="style">
        <slot />
    </span>
</template>
