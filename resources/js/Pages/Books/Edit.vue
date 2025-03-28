<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import type { Book } from '@/Types';
import type { PropType } from 'vue';

defineProps({
    book: Object as PropType<Book>
});
</script>

<template>
    <AppLayout title="Edit Book">
        <h2>Edit Book</h2>
    </AppLayout>
</template>
