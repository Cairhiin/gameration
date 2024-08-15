<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import FormInput from '../Custom/FormInput.vue';
import InputLabel from '../Custom/InputLabel.vue';
import PrimaryButton from '../Custom/PrimaryButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

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
    <app-layout>
        <form class="flex flex-col m-8 max-w-xl gap-4 mx-auto bg-highlight/25 shadow-md rounded-lg p-8"
            @submit.prevent="submit">
            <h2 class="text-center font-bold uppercase text-xl text-lightVariant mb-6">Create Developer</h2>

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

            <primary-button type="submit" class="mt-8" size="text-default">{{
                !isBeingEdited ? "Create" : "Update" }}</primary-button>
        </form>
    </app-layout>
</template>
