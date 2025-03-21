<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import AchievementForm from '@/Components/Forms/AchievementForm.vue';
import type { Achievement } from '@/Types';
import type { PropType } from 'vue';

defineProps({ achievement: Object as PropType<Achievement> });
</script>

<template>
    <app-layout title="Edit Achievement">
        <achievement-form :achievement="achievement" />
    </app-layout>
</template>
