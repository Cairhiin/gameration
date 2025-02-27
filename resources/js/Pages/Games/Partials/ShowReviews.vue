<script lang="ts" setup>
import { ref, defineProps, type PropType } from 'vue';
import type { Data, Game, Review } from '@/Types';
import ShowReview from '@/Components/Custom/ShowReview.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import TipTapEditor from '@/Components/Custom/TipTap/TipTapEditor.vue';
import type { InertiaPageProps } from '@/Types/inertia';
import InputLabel from '@/Components/InputLabel.vue';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const page = usePage<InertiaPageProps>();

const { reviews, review, game } = defineProps({
    review: {
        type: Object as PropType<Review>,
        required: false,
    },
    game: {
        type: Object as PropType<Game>,
        required: true,
    },
    reviews: Object as PropType<Data<Review>>
});

const form = useForm<{ game_id: string, user_id: string, content: string }>({
    game_id: review ? review.game_id : game.id,
    user_id: review ? review.user_id : page.props.auth.user.id,
    content: review ? review.content : '',
});

const userReviewIsShowing = ref<boolean>(false);

const submitForm = () => {
    form.clearErrors();

    if (form.content.length <= 50) {
        form.errors.content = 'Review must be at least 10 characters long';
        return;
    }

    const url = review.content ? 'games.reviews.update' : 'games.reviews.store';
    const method = review.content ? 'put' : 'post';

    form.submit(method, route(url, { game: game, review: review }), {
        preserveScroll: true,
        preserveState: "errors",
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <section class=" bg-darkVariant/25 px-8 py-4 my-6 rounded-xl">
        <h3 class="text-dark-highlight-variant font-bold uppercase text-sm py-4">Reviews</h3>
        <div v-if="reviews.data.length !== 0">

            <!-- User Reviews -->
            <div class="flex flex-col gap-4">
                <div v-for="review in reviews.data" :key="review.id">
                    <show-review :review="review" :rating="review.rating" />
                </div>
            </div>
        </div>

        <div v-if="reviews.data.length === 0 && !review?.content">
            <p>No reviews yet.</p>
        </div>

        <!-- Logged in User Review -->
        <div v-if="review?.content">
            <show-review :review="review" :rating="review.rating" />
        </div>
        <primary-button @click="userReviewIsShowing = !userReviewIsShowing">{{ `${review?.content ? 'Edit' : 'Add'}`
            }}
            Review</primary-button>
        <template v-if="userReviewIsShowing">
            <div class="mt-8">
                <form @submit.prevent="submitForm">
                    <input-label forHtml="name">Review</input-label>
                    <tip-tap-editor :default="form.content" v-model="form.content" />
                    <error-message v-if="form.errors.content || page.props.errors.createReview">{{
                        form.errors.content || page.props.errors.createReview.content }}</error-message>
                    <primary-button type="submit" class="btn btn-primary mt-4">Submit</primary-button>
                </form>
            </div>
        </template>
    </section>
</template>
