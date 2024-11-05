<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    icon: {
        type: String,
        default: '',
    },
    href: {
        type: String,
        default: '#',
    },
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'flex items-center gap-2 text-light font-bold  bg-highlight py-2 px-4 rounded hover:bg-highlight/75 ease-in-out duration-300'
        : 'flex items-center gap-2 text-light bg-highlight py-2 px-4 rounded hover:bg-highlight/75 ease-in-out duration-300';
});
</script>

<template>
    <Link :href="href" :class="classes">
    <i v-if="icon" :class="icon" class="text-xl"></i>
    <slot />
    </Link>
</template>
