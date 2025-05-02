<script lang="ts" setup>
import type { Book, Data } from '@/Types';
import { Link } from '@inertiajs/vue3';
import type { PropType } from 'vue';
import pagination from '@/Components/Custom/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import IndexSection from '@/Components/Custom/IndexSection.vue';
import image from '../../../images/missing_book_cover.png';

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
    <app-layout :title="`${type.charAt(0).toUpperCase() + String(type).slice(1)} Books`">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 container">
            <section class="w-full px-4 py-12 my-4 rounded-lg">
                <div class="container mx-auto max-w-sm md:max-w-lg lg:max-w-2xl xl:max-w-4xl">
                    <h2
                        class="mx-auto text-center md:text-left md:mx-0 mb-4 text-3xl font-bold uppercase text-lightVariant/75">
                        {{ type }}
                        Books
                    </h2>
                    <ul class="grid grid-cols-5 gap-4 lg:gap-8">
                        <li v-for="book in books.data" :key="book.id" class="relative mx-auto md:mx-0">
                            <Link :href="route('books.show', book.id)"><img
                                :src="book.image ? `/storage/${book.image}` : image" :alt="book.title"
                                class="object-cover h-full w-full max-w-32 hover:scale-105 transition-all duration-300" />
                            </Link>
                            <div
                                class="absolute bottom-1 left-1 px-2 py-1 rounded bg-lightVariant text-dark font-bold text-xs">
                                {{
                                    book.avg_rating.toFixed(1) }}</div>
                        </li>
                    </ul>
                    <div class="mt-4">
                        <pagination data="books" :links="books.links" />
                    </div>
                </div>
            </section>
        </div>
    </app-layout>
</template>
