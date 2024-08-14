<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    size: {
        type: String,
        default: 'text-xs'
    }
});
</script>
<template>
    <button :type="type" class="bg-highlight hover:bg-highlight/75 text-light font-bold py-1 px-4 rounded uppercase w-full
        focus:outline-none focus:ring-2 focus:ring-sky-700 focus:bg-highlight/75 transition ease-in-out duration-300"
        :class="size">
        <slot />
    </button>
</template>
