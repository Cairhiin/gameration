<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'inline-flex items-center py-3 border-b-2 border-highlight font-medium leading-5 text-light focus:outline-none focus:border-highlight-700 transition duration-150 ease-in-out'
        : 'inline-flex items-center py-3 border-b-2 border-transparent font-medium leading-5 text-lightVariant hover:text-light hover:border-lightVariant-300 focus:outline-none focus:text-lightVariant focus:border-lightVariant transition duration-150 ease-in-out';
});
</script>

<template>
    <Link :href="href" :class="classes">
    <slot />
    </Link>
</template>
