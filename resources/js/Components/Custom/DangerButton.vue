<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    size: {
        type: String,
        default: 'text-sm'
    }
});
</script>
<template>
    <button :type="type" class="bg-red-700 hover:bg-red-800 text-light font-bold py-2 px-4 rounded uppercase w-full
        focus:outline-none focus:ring-2 focus:ring-red-800 focus:bg-red-700/75 transition ease-in-out duration-300"
        :class="size">
        <slot />
    </button>
</template>
