<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import RatingComponent from '@/Components/Custom/Rating.vue';
import Pagination from '@/Components/Custom/Pagination.vue';
import type { PropType } from 'vue';
import type { Data, Rating } from '@/Types';

const { ratings } = defineProps({
    ratings: Object as PropType<Data<Rating>>
});
</script>

<template>
    <app-layout title="Ratings">
        <div>
            <div v-for="rating in ratings.data" :key="rating.game_id" class="border border-dark-highlight rounded-lg transition duration-150 ease-in-out
            drop-shadow-md cursor-pointer my-2">
                <Link :href="`/games/${rating.game.id}`">
                <div class="flex gap-2 justify-between px-4 py-2 border-inherit">
                    {{ rating.game.name }}
                    <rating-component :value="rating.rating" :rateable="false" />
                </div>
                </Link>
            </div>

            <pagination :links="ratings.links" />
        </div>
    </app-layout>
</template>
