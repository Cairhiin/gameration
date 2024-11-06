<script setup>
import { ref, computed } from 'vue';

defineProps({
    show: {
        type: Boolean,
        default: false
    }
});

const dropdown = ref(null);

const position = computed(() => {
    return dropdown.value?.getBoundingClientRect().x < window.innerWidth / 2 ? 'left-0' : 'right-0';
});
</script>

<template>
    <div v-if="show" ref="dropdown" :class="position"
        class="absolute top-6 w-72 z-50 rounded-lg bg-dark-highlight overflow-hidden shadow-lg">
        <div class="py-2">
            <slot />
        </div>
    </div>
</template>
