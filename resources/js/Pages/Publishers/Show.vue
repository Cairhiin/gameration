<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    publisher: Object
})
</script>

<template>
    <app-layout :title="publisher.name">
        <div>{{ publisher.name }}</div>
    </app-layout>
</template>
