<script lang="ts" setup>
import type { PropType } from 'vue';
import type { Review } from '@/Types';
import Rating from '@/Components/Custom/Rating.vue';
import { dateOptions } from '@/Utils';

const { review, rating } = defineProps({
    review: Object as PropType<Review>,
    rating: Number
});
</script>

<template>
    <div class="border-b-2 border-dark-highlight-variant/5 my-4">
        <div class="bg-dark-highlight-variant/5 px-2 py-1 flex justify-between items-center">
            <div>
                <div>{{ review.user?.username }}
                    <span class="text-sm text-lightVariant">{{ new Date(review.created_at).toLocaleDateString('fi-FI',
                        dateOptions)
                        }}</span>
                </div>
            </div>
            <div>
                <rating :value="rating ?? 0.0" :rateable="false" />
            </div>
        </div>
        <div v-html="review.content" class="m-4"></div>
    </div>
</template>
