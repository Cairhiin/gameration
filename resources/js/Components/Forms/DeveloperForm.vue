<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import FormSection from '@/Components/Forms/FormSection.vue';

const page = usePage();

const { developer } = defineProps({
    developer: Object
})

const isBeingEdited = !!developer

const form = useForm({
    name: developer ? developer.name : null,
    city: developer ? developer.city : null,
    country: developer ? developer.country : null,
    year: developer ? developer.year : null
});

const submit = () => {
    isBeingEdited ? form.put(route('developers.update', developer.id)) :
        form.post(route('developers.store'))
}
</script>

<template>
    <form-section :title="`${isBeingEdited ? 'Edit' : 'Create'} Developer`" @on-submit="submit">
        <template #form>

            <!-- Name -->
            <input-label forHtml="name">Name</input-label>
            <form-input type="text" name="name" id="name" v-model="form.name" />
            <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.name">{{
        page.props.errors.createDeveloper.name }}</error-message>

            <!-- City -->
            <input-label forHtml="city">City</input-label>
            <form-input type="text" name="city" id="city" v-model="form.city" />
            <div v-if="form.errors.city">{{ form.errors.city }}</div>
            <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.city">{{
        page.props.errors.createDeveloper.city }}</error-message>

            <!-- Country -->
            <input-label forHtml="country">Country</input-label>
            <form-input type="text" name="country" id="country" v-model="form.country" />
            <div v-if="form.errors.country">{{ form.errors.country }}</div>
            <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.country">{{
        page.props.errors.createDeveloper.country }}</error-message>

            <!-- Year -->
            <input-label forHtml="year">Year</input-label>
            <form-input type="text" name="year" id="year" v-model="form.year" />
            <div v-if="form.errors.year">{{ form.errors.year }}</div>
            <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.year">{{
        page.props.errors.createDeveloper.year }}</error-message>
        </template>

        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                Save
            </PrimaryButton>
        </template>
    </form-section>
</template>
