<script lang="ts" setup>
import { useForm, usePage } from '@inertiajs/vue3'
import FormSection from '@/Components/Forms/FormSection.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import type { PropType } from 'vue';
import type { PreserveStateOption } from '@inertiajs/core';
import type { Genre } from '@/Types';
import type { InertiaPageProps } from '@/Types/inertia';

const { genre } = defineProps({
    genre: Object as PropType<Genre>
});

const form = useForm<{ name: string }>({
    name: genre.name,
});

const { props } = usePage<InertiaPageProps>();

const submit = (): void => {
    form.put(route('genres.update', genre.id), {
        errorBag: 'updateGenre',
        preserveScroll: true,
        preserveState: "errors.updateGenre" as PreserveStateOption,
        onSuccess: () => form.reset()
    });
};
</script>

<template>
    <app-layout title="Edit Genre">
        <form-section title="Edit Genre" @on-submit="submit">
            <template #form>

                <!-- Name -->
                <input-label forHtml="name">Name</input-label>
                <form-input type="text" name="name" id="name" v-model="form.name" aria-required="true" required />
                <error-message v-if="props.errors.createGenre && props.errors.createGenre.name">{{
                    props.errors.createGenre.name }}</error-message>
            </template>

            <template #actions>
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                    Save
                </PrimaryButton>
            </template>
        </form-section>
    </app-layout>
</template>
