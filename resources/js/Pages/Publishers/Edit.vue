<script setup>
import PublisherForm from '@/Components/Forms/PublisherForm.vue'

defineProps({
    publisher: Object
});
</script>

<template>
    <publisher-form :publisher="publisher" />
</template>
