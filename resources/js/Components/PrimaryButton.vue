<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2
    bg-highlight border border-transparent rounded font-bold text-xs text-white uppercase tracking-widest
     hover:bg-highlight/50 focus:bg--highlight/50 active:-highlight/75 focus:outline-none focus:ring-2 focus:ring-indigo-500
     transition ease-in-out duration-300">
        <slot />
    </button>
</template>
