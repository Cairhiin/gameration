<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import type { PropType } from 'vue';
import type { Book } from '@/Types';
import IndexSection from '@/Components/Custom/IndexSection.vue';
import image from '../../../../images/missing_book_cover.png';

const { friends } = defineProps({
    friends: {
        type: Object as PropType<{ id: number; username: string; books: Book[]; }[]>,
        required: true
    }
});
console.log(friends);
</script>

<template>
    <index-section>
        <template #title>Friends Have Been Reading</template>
        <template #content>
            <div v-for="friend in friends" :key="friend.id" class="my-4 bg-lightVariant/5 rounded p-4">
                <span class="block font-bold text-lightVariant/80 text-default mb-2">{{ friend.username }} has
                    recently read...</span>
                <ul class="grid grid-cols-5 gap-4 lg:gap-8">
                    <li v-if="friend.books.length === 0" class="col-span-5">
                        No books read yet.
                    </li>
                    <li v-else v-for="book in friend.books" :key="book.id">
                        <Link :href="route('books.show', book.id)" class="flex flex-col items-center gap-2">
                        <img :src="book.image ? `/storage/${book.image}` : image" :alt="book.title" class="object-cover max-w-32 w-full hover:scale-105 transition-all duration-300
                            " />
                        <div class="font-bold">
                            {{ book.pivot.rating.toFixed(1) }}</div>
                        </Link>
                    </li>
                </ul>
            </div>
        </template>
    </index-section>
</template>
