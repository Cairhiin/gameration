<script setup>
defineProps({
    title: String,
    level: {
        type: String,
        default: 'h3'
    }
})
</script>

<template>
    <h3 v-if="level === 'h3'"
        class="font-bold uppercase text-xl inline-block bg-gradient-to-r from-blue-200 to-cyan-200 text-transparent bg-clip-text">
        <slot />
    </h3>
    <h4 v-else class="font-bold uppercase text-lg text-lightVariant">
        <slot />
    </h4>
</template>
