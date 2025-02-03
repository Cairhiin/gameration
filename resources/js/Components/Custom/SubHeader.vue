<script lang="ts" setup>
defineProps({
    title: String,
    level: {
        type: String,
        default: 'h3'
    }
})
</script>

<template>
    <h3 v-if="level === 'h3'" class="font-bold uppercase text-xl text-lightVariant">
        <slot />
    </h3>
    <h4 v-else class="font-bold uppercase text-lg inline-block text-lightVariant">
        <slot />
    </h4>
</template>
