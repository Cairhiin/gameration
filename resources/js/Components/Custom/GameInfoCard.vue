<script lang="ts" setup>
import { computed } from 'vue';

const props = withDefaults(defineProps<{
    heading: string,
    icon: string,
    value: string | number
    precision: number
}>(), {
    precision: 0
});

const icon = computed<string>(() => {
    return `fa-solid ${props.icon}`
});
</script>

<template>
    <div class="flex justify-between items-center rounded-3xl bg-dark-box/60 px-5 py-3">
        <div>
            <div class="text-lightVariant">{{ heading }}</div>
            <div>{{ typeof value === 'number' ? value.toFixed(precision) : value }}</div>
        </div>
        <div><i class="text-lightVariant text-lg" :class="icon"></i></div>
    </div>
</template>
