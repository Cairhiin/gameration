<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    genre: Object
});
</script>

<template>
    <app-layout title="Edit Genre">
        {{ genre }}
    </app-layout>
</template>
