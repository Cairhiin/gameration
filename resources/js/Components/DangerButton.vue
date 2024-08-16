<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded
    font-semibold text-xs text-white uppercase tracking-widest
    hover:bg-red-800 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-800
    transition ease-in-out duration-300">
        <slot />
    </button>
</template>
