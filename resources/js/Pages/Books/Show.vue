<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import type { Book } from '@/Types';
import type { PropType } from 'vue';

defineProps({
    book: Object as PropType<Book>
});
</script>

<template>
    <AppLayout :title="book?.title">
        <h2>{{ book?.title }}</h2>
        <p>{{ book?.description }}</p>
    </AppLayout>
</template>
