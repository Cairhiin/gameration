<script setup>
import { computed } from 'vue';

const { variant } = defineProps({
    variant: {
        type: String,
        default: 'dark'
    }
});

const style = computed(() => {
    if (variant === 'dark') {
        return 'bg-highlight text-light';
    }

    if (variant === 'light') {
        return 'bg-light text-dark';
    }
});
</script>

<template>
    <span class="rounded-3xl px-4 py-1 text-base" :class="style">
        <slot />
    </span>
</template>
