<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BookForm from '@/Components/Forms/BookForm.vue';
import type { Book } from '@/Types';
import type { PropType } from 'vue';

defineProps({
    book: Object as PropType<Book>
});
</script>

<template>
    <AppLayout title="Edit Book">
        <book-form :book="book" />
    </AppLayout>
</template>
