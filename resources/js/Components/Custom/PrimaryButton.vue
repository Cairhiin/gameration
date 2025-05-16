<script lang="ts" setup>
import { computed, type PropType } from 'vue';

const { variant, size } = defineProps({
    type: {
        type: String as PropType<'button' | 'submit' | 'reset'>,
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

const styles = computed<string>(() => {
    let styles = size + " cursor-pointer font-bold py-2 px-4 rounded uppercase focus:outline-none focus:ring-2 focus:ring-dark-highlight-variant focus:bg-dark focus:text-dark-highlight-variant transition ease-in-out duration-300";

    if (variant === 'normal') {
        styles += ' bg-dark-highlight-variant hover:bg-transparent text-dark hover:text-dark-highlight-variant';
    }

    if (variant === 'invert') {
        styles += ' bg-transparent text-dark-highlight-variant';
    }

    return styles;
});
</script>
<template>
    <button :type="type" :class="styles" class="inline-block" @click="$emit('click')">
        <slot />
    </button>
</template>
