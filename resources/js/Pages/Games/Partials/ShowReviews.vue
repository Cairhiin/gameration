<script lang="ts" setup>
import { defineProps, type PropType } from 'vue';
import type { Data, Review } from '@/Types';
import ShowReview from '@/Components/Custom/ShowReview.vue';

const { reviews } = defineProps({
    reviews: Object as PropType<Data<Review>>
});
</script>

<template>
    <div class="flex flex-col gap-4">
        <div v-for="review in reviews.data" :key="review.id">
            <show-review :review="review" :value="review.rating" />
        </div>
    </div>
</template>
