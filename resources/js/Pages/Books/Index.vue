<script lang="ts" setup>
import { ref, type PropType } from 'vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import type { Book, Genre } from '@/Types';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminCreateSection from '@/Components/Custom/AdminCreateSection.vue';
import GenreSection from '@/Pages/Books/Partials/GenreSection.vue';
import NewBooksSection from '@/Pages/Books/Partials/NewBooksSections.vue';
import TrendingBooksSection from '@/Pages/Books/Partials/TrendingBooksSection.vue';
import RandomBooksSection from '@/Pages/Books/Partials/RandomBooksSection.vue';
import RandomFriendsSection from '@/Pages/Books/Partials/RandomFriendsSection.vue';

const { genres, books, trendingBooks, popularBooks } = defineProps({
    genres: {
        type: Array as PropType<Genre[]>,
        required: true
    },
    books: {
        type: Array as PropType<Book[]>,
        required: true
    },
    trendingBooks: {
        type: Object as PropType<Book[]>,
        required: true
    },
    popularBooks: {
        type: Array as PropType<Book[]>,
        required: true
    },
    randomFriends: {
        type: Array as PropType<{ id: number; username: string; books: Book[]; }[]>,
        required: true
    }
});

const form = useForm({
    search: '',
});

const submit = () => {
    router.get(route('books.search', { search: form.search }));
};

const isLoading = ref<boolean>(false);
</script>

<template>
    <app-layout title="Books">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex justify-between ml-4">

            </nav>
            <!-- Genres -->
            <genre-section :genres="genres" />

            <!-- New Releases -->
            <new-books-section :books="books" />

            <!-- Trending Books -->
            <trending-books-section :books="trendingBooks" />

            <!-- Random Popular Books -->
            <random-books-section :books="popularBooks" />

            <!-- Random Friends -->
            <random-friends-section :friends="randomFriends" v-if="randomFriends.length" />

            <!-- Admin Create Section -->
            <admin-create-section />
        </div>
    </app-layout>
</template>
