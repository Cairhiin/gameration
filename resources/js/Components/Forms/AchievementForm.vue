<script lang="ts" setup>
import { useForm, usePage } from '@inertiajs/vue3';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import FormSection from '@/Components/Forms/FormSection.vue';
import type { Achievement } from '@/Types';
import type { PropType } from 'vue';
import type { InertiaPageProps } from '@/Types/inertia';

const { achievement } = defineProps({
    achievement: Object as PropType<Achievement>,
});

const page = usePage<InertiaPageProps>();

const form = useForm<{ title: string, description: string, points: number, image: string }>({
    title: achievement.title ?? '',
    description: achievement.description ?? '',
    points: achievement.points ?? 0,
    image: achievement.image ?? '',
});

const submit = () => {
    if (achievement.id) {
        form.put(route('achievements.update', achievement.id));
    } else {
        form.post(route('achievements.store'));
    }
};
</script>

<template>
    <form-section title="Create Achievement">
        <form @submit.prevent="submit">
            <div>
                <input-label for="title">Title</input-label>
                <form-input id="title" v-model="form.title" type="text" />
                <error-message
                    v-if="page.props.errors.createAchievement && page.props.errors.createAchievement.title">{{
                        page.props.errors.createAchievement.title }}</error-message>
                <error-message v-if="form.errors.title">{{ form.errors.title
                    }}
                </error-message>
            </div>
            <div>
                <input-label for="description">Description</input-label>
                <textarea id="description" v-model="form.description"></textarea>
                <error-message
                    v-if="page.props.errors.createAchievement && page.props.errors.createAchievement.description">{{
                        page.props.errors.createAchievement.description }}</error-message>
                <error-message v-if="form.errors.description">{{ form.errors.description
                    }}</error-message>
            </div>
            <div>
                <input-label for="points">Points</input-label>
                <form-input id="points" v-model="form.points" type="number" />
                <error-message
                    v-if="page.props.errors.createAchievement && page.props.errors.createAchievement.points">{{
                        page.props.errors.createAchievement.points }}</error-message>
                <error-message v-if="form.errors.points">{{ form.errors.points }}</error-message>
            </div>
            <div>
                <input-label for="image">Image</input-label>
                <form-input id="image" v-model="form.image" type="text" />
            </div>
            <div>
                <primary-button type="submit">Create</primary-button>
            </div>
        </form>
    </form-section>
</template>
