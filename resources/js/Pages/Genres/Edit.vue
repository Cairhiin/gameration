<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import FormSection from '@/Components/Forms/FormSection.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const { genre } = defineProps({
    genre: Object
});

const form = useForm({
    name: genre.name,
});

const page = usePage();

const submit = () => {
    form.put(route('genres.update', genre.id), {
        errorBag: 'updateGenre',
        preserveScroll: true,
        preserveState: "errors.updateGenre",
        onSuccess: () => form.reset()
    })
}
</script>

<template>
    <app-layout title="Edit Genre">
        <form-section title="Edit Genre" @on-submit="submit">
            <template #form>

                <!-- Name -->
                <input-label forHtml="name">Name</input-label>
                <form-input type="text" name="name" id="name" v-model="form.name" aria-required="true" required />
                <error-message v-if="page.props.errors.createGenre && page.props.errors.createGenre.name">{{
            page.props.errors.createGenre.name }}</error-message>
            </template>

            <template #actions>
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                    Save
                </PrimaryButton>
            </template>
        </form-section>
    </app-layout>
</template>
