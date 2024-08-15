<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    developer: Object
})
</script>

<template>
    <app-layout :title="developer.name">
        <div>{{ developer.name }}</div>
    </app-layout>
</template>
