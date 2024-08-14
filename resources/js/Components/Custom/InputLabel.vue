<script setup>
defineProps({
    forHtml: String
});
</script>

<template>
    <label class="block font-medium text-sm text-light uppercase mt-2 -mb-2" :for="forHtml">
        <slot />
    </label>
</template>
