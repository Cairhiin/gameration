<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import FormInput from '../Custom/FormInput.vue';
import InputLabel from '../Custom/InputLabel.vue';
import PrimaryButton from '../Custom/PrimaryButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

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
    <form class="flex flex-col m-8 max-w-xl gap-4 mx-auto border border-darkVariant shadow-dark-sm rounded-lg p-8"
        @submit.prevent="submit">
        <h2 class="text-center font-bold uppercase text-xl text-lightVariant mb-6">Create publisher</h2>

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

        <primary-button type="submit" class="mt-8" size="text-default">{{
            !isBeingEdited ? "Create" : "Update" }}</primary-button>
    </form>
</template>
