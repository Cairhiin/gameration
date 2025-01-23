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
            <div v-for="rating in ratings.data" :key="rating.id" class="border border-dark-highlight rounded-lg transition duration-150 ease-in-out
            drop-shadow-md cursor-pointer my-2">
                <div class="flex gap-2 justify-between px-4 py-2 border-inherit">
                    {{ rating.game.name }}
                    <rating :value="rating.rating" :rateable="false" />
                </div>
            </div>

            <pagination :links="ratings.links" />
        </div>
    </app-layout>
</template>
