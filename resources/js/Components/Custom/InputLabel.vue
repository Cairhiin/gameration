<script lang="ts" setup>
defineProps({
    forHtml: String,
    classObject: {
        type: String,
        default: ''
    }
});
</script>

<template>
    <label class="block font-medium text-sm text-light uppercase mt-2 -mb-2" :for="forHtml" :class="classObject">
        <slot />
    </label>
</template>
