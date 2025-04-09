<script lang="ts" setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import type { Genre } from '@/Types';
import type { PropType } from 'vue';
import IndexSection from '@/Components/Custom/IndexSection.vue';

defineProps({
    genres: {
        type: Array as PropType<Genre[]>,
        required: true
    }
});

const form = useForm({
    search: '',
});

const submit = () => {
    router.get(route('books.search', { search: form.search }));
};
</script>

<template>
    <index-section>
        <template #title>Browse Books by Genre</template>
        <template #content>
            <ul class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-2 text-default">
                <li v-for="genre in genres" :key="genre.id">
                    <Link :href="route('genres.show', genre.id)">{{ genre.name }}</Link>
                </li>
            </ul>
            <form @submit.prevent="submit" class="mt-4">
                <input type="text" v-model="form.search" placeholder="Search by title | author | ISBN" class="w-64" />
            </form>
        </template>
    </index-section>
</template>
