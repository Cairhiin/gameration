<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Rating from '@/Components/Custom/Rating.vue';
import Pagination from '@/Components/Custom/Pagination.vue';

const { ratings } = defineProps({
    ratings: Object
});
</script>

<template>
    <app-layout title="Ratings">
        <div>
            <div v-for="rating in ratings.data" :key="rating.id"
                class="border border-dark-highlight bg-gradient-to-br from-dark-box/60 to-dark-box/20
                px-8 py-4 rounded-lg shadow my-2 flex gap-2 justify-between cursor-pointer hover:bg-dark-highlight-variant transition-all duration-300">
                {{ rating.game.name }}
                <rating :value="rating.rating" :rateable="false" />
            </div>

            <pagination :links="ratings.links" />
        </div>
    </app-layout>
</template>
