<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { capitalize } from '@/Utils/index.ts';

defineProps({
    publishers: Array
});
</script>

<template>
    <app-layout title="Publishers">
        <h2>Publishers</h2>
        <ul>
            <li v-for="publisher in publishers" :key="publisher.id">
                {{ capitalize(publisher.name) }}
            </li>
        </ul>
    </app-layout>
</template>
