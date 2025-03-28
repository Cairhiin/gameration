<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import type { Book, Data } from '@/Types';
import type { PropType } from 'vue';

defineProps({
    books: Object as PropType<Data<Book>>
});
</script>

<template>
    <AppLayout title="Books">
        <h2>Books</h2>
    </AppLayout>
</template>
