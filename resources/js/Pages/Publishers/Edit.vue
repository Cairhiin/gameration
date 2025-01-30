<script lang="ts" setup>
import PublisherForm from '@/Components/Forms/PublisherForm.vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import type { Publisher } from '@/Types';
import type { PropType } from 'vue';

defineProps({
    publisher: Object as PropType<Publisher>
});
</script>

<template>
    <app-layout :title="publisher.name">
        <publisher-form :publisher="publisher" />
    </app-layout>
</template>
