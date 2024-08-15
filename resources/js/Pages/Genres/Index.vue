<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { capitalize } from '@/Utils/index.ts';

defineProps({
    genres: Array
});
</script>

<template>
    <app-layout title="Genres">

        <template #header>
            Genres
        </template>
        <div>
            <ul>
                <li v-for="genre in genres" :key="genre.id">
                    {{ capitalize(genre.name) }}
                </li>
            </ul>
        </div>
    </app-layout>
</template>
