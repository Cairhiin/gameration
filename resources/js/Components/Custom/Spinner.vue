<script setup>
import { computed } from 'vue';

const props = defineProps({
    isSpinning: {
        type: Boolean,
        default: false
    },
    size: {
        type: String,
        default: 'text-3xl'
    }
});

const styles = computed(() => {
    return {
        'fa-spin': props.isSpinning,
        'text-4xl': props.size === 'text-4xl',
        'text-5xl': props.size === 'text-5xl',
        'text-3xl': props.size === 'text-3xl',
        'text-2xl': props.size === 'text-2xl',
        'text-xl': props.size === 'text-xl',
        'text-lg': props.size === 'text-lg',
        'text-sm': props.size === 'text-sm',
        'text-xs': props.size === 'text-xs',
    }
});
</script>

<template>
    <i class="fa fa-spinner text-3xl text-lightVariant" :class="styles"></i>
</template>
