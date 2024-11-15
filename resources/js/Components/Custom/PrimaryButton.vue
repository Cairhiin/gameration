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
    <button :type="type" class="bg-dark-highlight-variant hover:bg-highlight/75 text-dark hover:text-light font-bold py-2 px-4 rounded uppercase w-full
        focus:outline-none focus:ring-2 focus:ring-sky-700 focus:bg-highlight/75 transition ease-in-out duration-300"
        :class="size">
        <slot />
    </button>
</template>
