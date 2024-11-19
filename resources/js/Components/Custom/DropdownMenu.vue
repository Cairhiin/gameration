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
    <Transition name="dropdown-transition" enter-active-class="duration-300 ease-out"
        enter-from-class="transform top-0 opacity-0" enter-to-class="top-6 opacity-100"
        leave-active-class="duration-300 ease-in" leave-from-class="opacity-100" leave-to-class="transform opacity-0">
        <div v-if="show" ref="dropdown" :class="position"
            class="absolute w-72 z-50 rounded-lg bg-dark-highlight overflow-hidden shadow-lg">
            <div class="p-2">
                <slot />
            </div>
        </div>
    </Transition>
</template>
