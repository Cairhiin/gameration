<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import type { PropType } from 'vue';
import type { Book } from '@/Types';
import IndexSection from '@/Components/Custom/IndexSection.vue';
import image from '../../../../images/missing_book_cover.png';

defineProps({
    books: {
        type: Array as PropType<Book[]>,
        required: true
    }
});
</script>

<template>
    <index-section>
        <template #title>Recently Published Books</template>
        <template #content>
            <ul class="grid grid-cols-5 gap-4 lg:gap-8">
                <li v-for="book in books" :key="book.id">
                    <Link :href="route('books.show', book.id)"><img :src="book.image ? `/storage/${book.image}` : image"
                        :alt="book.title"
                        class="object-cover h-full w-full max-w-32 hover:scale-105 transition-all duration-300" />
                    </Link>
                </li>
            </ul>
        </template>
    </index-section>
</template>
