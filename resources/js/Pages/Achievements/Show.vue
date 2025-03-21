<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import type { Achievement } from '@/Types';
import type { PropType } from 'vue';

defineProps({
    achievement: Object as PropType<Achievement>,
});
</script>

<template>
    <app-layout :title="achievement.name">
        <h1>Show Achievement</h1>
    </app-layout>
</template>
