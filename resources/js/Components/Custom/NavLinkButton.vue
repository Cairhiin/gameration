<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import PrimaryButton from './PrimaryButton.vue';

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
    variant: String
});
</script>

<template>
    <primary-button :variant="variant">
        <Link :href="href" class="flex items-center gap-2">
        <i v-if="icon" :class="icon" class="text-xl "></i>
        <slot />
        </Link>
    </primary-button>
</template>
