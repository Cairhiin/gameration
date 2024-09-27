<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import FormSection from '@/Components/Forms/FormSection.vue';

const page = usePage();

const { publisher } = defineProps({
    publisher: Object
})

const isBeingEdited = !!publisher

const form = useForm({
    name: publisher ? publisher.name : null,
    city: publisher ? publisher.city : null,
    country: publisher ? publisher.country : null,
    year: publisher ? publisher.year : null
});

const submit = () => {
    isBeingEdited ? form.put(route('publishers.update', publisher.id)) :
        form.post(route('publishers.store'))
}
</script>

<template>
    <form-section :title="`${isBeingEdited ? 'Edit' : 'Create'} Publisher`" @on-submit="submit">
        <template #form>

            <!-- Name -->
            <input-label forHtml="name">Name</input-label>
            <form-input type="text" name="name" id="name" v-model="form.name" />
            <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.name">{{
        page.props.errors.createDeveloper.name }}</error-message>

            <!-- City -->
            <input-label forHtml="city">City</input-label>
            <form-input type="text" name="city" id="city" v-model="form.city" />
            <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.city">{{
        page.props.errors.createDeveloper.city }}</error-message>

            <!-- Country -->
            <input-label forHtml="country">Country</input-label>
            <form-input type="text" name="country" id="country" v-model="form.country" />
            <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.country">{{
        page.props.errors.createDeveloper.country }}</error-message>

            <!-- Year -->
            <input-label forHtml="year">Year</input-label>
            <form-input type="text" name="year" id="year" v-model="form.year" />
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
