<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';

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
    <form class="flex flex-col m-8 max-w-xl gap-4 mx-auto bg-black shadow-md rounded-lg p-8" @submit.prevent="submit">

        <!-- Name -->
        <label for="name">Name</label>
        <input type="text" name="name" id="name" v-model="form.name" />
        <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.name">{{
        page.props.errors.createDeveloper.name }}</error-message>

        <!-- City -->
        <label for="city">City</label>
        <input type="text" name="city" id="city" v-model="form.city" />
        <div v-if="form.errors.city">{{ form.errors.city }}</div>
        <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.city">{{
        page.props.errors.createDeveloper.city }}</error-message>

        <!-- Country -->
        <label for="country">Country</label>
        <input type="text" name="country" id="country" v-model="form.country" />
        <div v-if="form.errors.country">{{ form.errors.country }}</div>
        <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.country">{{
        page.props.errors.createDeveloper.country }}</error-message>

        <!-- Year -->
        <label for="year">Year</label>
        <input type="text" name="year" id="year" v-model="form.year" />
        <div v-if="form.errors.year">{{ form.errors.year }}</div>
        <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.year">{{
        page.props.errors.createDeveloper.year }}</error-message>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{
        !isBeingEdited ? "Create" : "Update" }}</button>
    </form>
</template>
