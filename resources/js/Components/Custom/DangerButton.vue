<script lang="ts" setup>
import { computed, type PropType } from 'vue';

const { variant, size, type } = defineProps({
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

    let styles = size;

    variant === 'normal' ? styles += ' bg-red-700 hover:bg-red-800 focus:bg-red-700/75 text-light' :
        styles += ' bg-transparent text-red-500 hover:bg-transparent';

    return styles;
});
</script>
<template>
    <button :type="type" class=" hover:bg-red-800 font-bold py-2 px-4 rounded uppercase w-full
        focus:outline-none focus:ring-2 focus:ring-red-800 transition ease-in-out duration-300" :class="styles">
        <slot />
    </button>
</template>
