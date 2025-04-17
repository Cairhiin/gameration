<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import type { Person } from '@/Types';
import type { PropType } from 'vue';

const { person } = defineProps({
    person: Object as PropType<Person>
});
</script>

<template>
    <app-layout title="Create Person">
        {{ person.name }}
    </app-layout>
</template>
