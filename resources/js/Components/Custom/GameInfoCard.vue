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
    <div class="rounded-3xl bg-darkVariant/25 px-5 py-3">
        <div class="flex justify-between">
            <div class="text-lightVariant">{{ heading }}</div>
            <div><i class="text-lightVariant" :class="icon"></i></div>
        </div>
        <div>{{ typeof value === 'number' ? value.toFixed(precision) : value }}</div>
    </div>
</template>
