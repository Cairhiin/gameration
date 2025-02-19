<script lang="ts" setup>
import { useForm, usePage } from '@inertiajs/vue3';
import TipTapEditor from '@/Components/Custom/TipTap/TipTapEditor.vue';
import type { Review } from '@/Types';
import { computed, ref, type PropType } from 'vue';
import type { InertiaPageProps } from '@/Types/inertia';
import InputLabel from '@/Components/InputLabel.vue';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ShowReview from '@/Components/Custom/ShowReview.vue';

const page = usePage<InertiaPageProps>();

const { review, game_id, rating } = defineProps({
    review: {
        type: Object as PropType<Review>,
        required: false,
    },
    game_id: {
        type: String,
        required: true,
    },
    rating: {
        type: Number,
        required: false,
    },
});

const form = useForm<{ game_id: string, user_id: string, content: string }>({
    game_id: review ? review.game_id : game_id,
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

    form.submit(method, route(url, { game: game_id, review: review }), {
        preserveScroll: true,
        preserveState: "errors",
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <div v-if="review.content">
        <show-review :review="review" :rating="rating" />
    </div>
    <primary-button @click="userReviewIsShowing = !userReviewIsShowing">{{ `${review.content ? 'Edit' : 'Add'}`
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
</template>
