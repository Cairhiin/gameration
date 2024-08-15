<script setup>
import PublisherForm from '@/Components/Forms/PublisherForm.vue'
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    publisher: Object
});
</script>

<template>
    <app-layout :title="publisher.name">
        <publisher-form :publisher="publisher" />
    </app-layout>
</template>
