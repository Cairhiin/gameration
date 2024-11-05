<script setup>
defineProps({
    show: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <div v-if="show" class="absolute top-6 left-0 w-72 z-50 rounded-lg bg-dark-highlight overflow-hidden shadow-lg">
        <div class="py-2">
            <slot />
        </div>
    </div>
</template>
