<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import type { PropType } from 'vue';

defineProps({
    achievements: Array as PropType<{ id: number; name: string }[]>,
});
</script>

<template>
    <app-layout title="Achievements">
        <h1>Achievements</h1>
        <section>
            <ul v-if="achievements.length">
                <li v-for="achievement in achievements" :key="achievement.id">
                    {{ achievement.name }}
                </li>
            </ul>
            <div v-else>
                <p>No achievements found.</p>
            </div>
        </section>
    </app-layout>
</template>
