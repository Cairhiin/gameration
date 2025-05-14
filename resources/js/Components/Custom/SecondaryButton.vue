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

const emit = defineEmits(['click']);

const styles = computed<string>(() => {
    let styles = size + " cursor-pointer font-bold text-xs py-2 px-4 rounded uppercase focus:outline-none focus:ring-2 focus:ring-dark-highlight-variant focus:bg-dark focus:text-dark-highlight-variant transition ease-in-out duration-500";

    if (variant === 'normal') {
        styles += ' bg-dark-highlight-variant/25 hover:bg-transparent text-light hover:text-dark-highlight-variant/50';
    }

    if (variant === 'invert') {
        styles += ' bg-transparent hover:text-dark-highlight-variant/50 text-light/60';
    }

    if (variant === 'outline') {
        styles += ' bg-transparent hover:text-dark-highlight-variant/50 text-light/70 border border-dark-box hover:border-dark-highlight-variant/50';
    }

    return styles;
});
</script>
<template>
    <button :type="type" :class="styles" class="inline-block" @click.stop="emit('click')">
        <slot />
    </button>
</template>
