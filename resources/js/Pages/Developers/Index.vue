<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { capitalize } from '@/Utils/index.ts';

defineProps({
    developers: Array
});
</script>

<template>
    <app-layout title="Developers">
        <h2>Developers</h2>
        <ul>
            <li v-for="developer in developers" :key="developer.id">
                {{ capitalize(developer.name) }}
            </li>
        </ul>
    </app-layout>
</template>
