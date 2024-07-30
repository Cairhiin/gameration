<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: null,
    city: null,
    country: null,
    year: null
});
</script>

<template>
    <form class="flex flex-col m-8 max-w-xl gap-4 mx-auto bg-black shadow-md rounded-lg p-8"
        @submit.prevent="form.post(route('developers.store'))">

        <!-- Name -->
        <label for="name">Name</label>
        <input type="text" name="name" id="name" v-model="form.name" />
        <div v-if="form.errors.name">{{ form.errors.name }}</div>

        <!-- City -->
        <label for="city">City</label>
        <input type="text" name="city" id="city" v-model="form.city" />
        <div v-if="form.errors.city">{{ form.errors.city }}</div>

        <!-- Country -->
        <label for="country">Country</label>
        <input type="text" name="country" id="country" v-model="form.country" />
        <div v-if="form.errors.country">{{ form.errors.country }}</div>

        <!-- Year -->
        <label for="year">Year</label>
        <input type="text" name="year" id="year" v-model="form.year" />
        <div v-if="form.errors.year">{{ form.errors.year }}</div>

        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</template>
