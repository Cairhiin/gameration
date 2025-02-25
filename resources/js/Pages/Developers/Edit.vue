<script lang="ts" setup>
import DeveloperForm from '@/Components/Forms/DeveloperForm.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import type { Developer } from '@/Types';
import type { PropType } from 'vue';

defineProps({
    developer: Object as PropType<Developer>,
});
</script>

<template>
    <app-layout title="Create Developer">
        <developer-form :developer="developer" />
    </app-layout>
</template>
