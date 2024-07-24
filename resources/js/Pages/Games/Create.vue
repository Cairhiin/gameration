<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: null,
    description: null,
    genre: null,
    developer: null,
    publisher: null
});
</script>

<template>
    <form class="flex flex-col m-8 max-w-xl gap-4 mx-auto bg-black shadow-md rounded-lg p-8"
        @submit.prevent="form.post(route('games.store'))">

        <!-- Name -->
        <input type="text" name="name" id="name" placeholder="Name" />
        <div v-if="form.errors.name">{{ form.errors.name }}</div>

        <!-- Description -->
        <input type="text" name="description" id="description" placeholder="Description" />
        <div v-if="form.errors.description">{{ form.errors.description }}</div>

        <!-- Genre -->
        <select name="genre" id="genre"></select>
        <div v-if="form.errors.genre">{{ form.errors.genre }}</div>

        <!-- Developer -->
        <select name="developer" id="developer"></select>
        <div v-if="form.errors.developer">{{ form.errors.developer }}</div>

        <!-- Publisher -->
        <select name="publisher" id="publisher"></select>
        <div v-if="form.errors.publisher">{{ form.errors.publisher }}</div>

        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</template>
