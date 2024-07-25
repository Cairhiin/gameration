<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: null,
});
</script>

<template>
    <form class="flex flex-col m-8 max-w-xl gap-4 mx-auto bg-black shadow-md rounded-lg p-8"
        @submit.prevent="form.post(route('developers.store'))">

        <!-- Name -->
        <input type="text" name="name" id="name" placeholder="Name" v-model="form.name" />
        <div v-if="form.errors.name">{{ form.errors.name }}</div>

        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</template>
