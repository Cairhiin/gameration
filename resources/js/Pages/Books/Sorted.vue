<script setup lang="ts">
import type { Book, Data } from '@/Types';
import type { PropType } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const { books, type } = defineProps({
    books: {
        type: Object as PropType<Data<Book>>,
        required: true,
    },
    type: {
        type: String,
        default: 'Newest',
    },
});
</script>

<template>
    <app-layout :title="`${type} Books`">
        <h2 class="text-2xl font-bold mb-4">{{ type }} Books</h2>
        <ul>
            <li v-for="book in books.data" :key="book.id" class="mb-4">
                <h2 class="text-xl font-bold">{{ book.title }}</h2>
                <p class="text-gray-600"><span v-for="author in book.authors" :key="author.id">{{ author.name }},
                    </span>
                </p>
                <p class="text-gray-500">{{ book.published_at }}</p>
                <p class="text-gray-500">Average Rating: {{ book.avg_rating.toFixed(1) }}</p>
            </li>
        </ul>
    </app-layout>
</template>
