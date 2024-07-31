<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';

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
    <form class="flex flex-col m-8 max-w-xl gap-4 mx-auto bg-black shadow-md rounded-lg p-8" @submit.prevent="submit">

        <!-- Name -->
        <label for="name">Name</label>
        <input type="text" name="name" id="name" v-model="form.name" />
        <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.name">{{
        page.props.errors.createDeveloper.name }}</error-message>

        <!-- City -->
        <label for="city">City</label>
        <input type="text" name="city" id="city" v-model="form.city" />
        <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.city">{{
        page.props.errors.createDeveloper.city }}</error-message>

        <!-- Country -->
        <label for="country">Country</label>
        <input type="text" name="country" id="country" v-model="form.country" />
        <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.country">{{
        page.props.errors.createDeveloper.country }}</error-message>

        <!-- Year -->
        <label for="year">Year</label>
        <input type="text" name="year" id="year" v-model="form.year" />
        <error-message v-if="page.props.errors.createDeveloper && page.props.errors.createDeveloper.year">{{
        page.props.errors.createDeveloper.year }}</error-message>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{
        !isBeingEdited ? "Create" : "Update" }}</button>
    </form>
</template>
