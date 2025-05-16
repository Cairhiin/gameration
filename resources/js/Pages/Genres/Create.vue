<script lang="ts" setup>
import { useForm, usePage } from '@inertiajs/vue3'
import FormSection from '@/Components/Forms/FormSection.vue';
import SecondaryButton from '@/Components/Custom/SecondaryButton.vue';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import type { InertiaPageProps } from '@/Types/inertia';

const form = useForm<{ name: string }>({
    name: null,
});

const { props } = usePage<InertiaPageProps>();

const submit = (): void => {
    form.post(route('genres.store'))
}
</script>

<template>
    <app-layout title="Create Genre">
        <form-section title="Create Genre" @on-submit="submit">
            <template #form>
                <fieldset class="flex flex-col m-8 gap-4">
                    <!-- Name -->
                    <input-label forHtml="name">Name</input-label>
                    <form-input type="text" name="name" id="name" v-model="form.name" aria-required="true" required />
                    <error-message v-if="props.errors.createGenre && props.errors.createGenre.name">{{
                        props.errors.createGenre.name }}</error-message>
                </fieldset>
            </template>

            <template #actions>
                <SecondaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                    Save
                </SecondaryButton>
            </template>
        </form-section>
    </app-layout>
</template>
