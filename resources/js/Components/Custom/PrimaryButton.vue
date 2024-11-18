<script setup>
import { computed } from 'vue';

const { variant, size } = defineProps({
    type: {
        type: String,
        default: 'button',
    },
    size: {
        type: String,
        default: 'text-sm'
    },
    variant: {
        type: String,
        default: 'normal'
    }
});

const styles = computed(() => {
    let styles = size + " font-bold py-2 px-4 rounded uppercase w-full focus:outline-none focus:ring-2 focus:ring-sky-700 focus:bg-highlight/75 transition ease-in-out duration-300";

    if (variant === 'normal') {
        styles += ' bg-dark-highlight-variant hover:bg-highlight/75 text-dark hover:text-light';
    }

    if (variant === 'invert') {
        styles += ' bg-transparent text-light hover:bg-transparent';
    }

    return styles;
});
</script>
<template>
    <button :type="type" :class="styles">
        <slot />
    </button>
</template>
