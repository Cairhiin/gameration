<script setup>
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3'
import axios from 'axios';
import { debounce, capitalize } from '@/Utils/index.ts';

const genres = ref([]);
const debounceFn = ref(null);

onMounted(() => {
    debounceFn.value = debounce((event) => getGenres(event), 800)
});

const form = useForm({
    name: null,
    description: null,
    genre: null,
    developer: null,
    publisher: null
});

const getGenres = (event) => {
    axios.post(route('genres.search'), { search: event.target.value }).then(response => {
        genres.value = response.data;
    });
}

</script>

<template>
    <form class="flex flex-col m-8 max-w-xl gap-4 mx-auto bg-black shadow-md rounded-lg p-8"
        @submit.prevent="form.post(route('games.store'))">

        <!-- Name -->
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" placeholder="Name" v-model="form.name" />
        <div v-if="form.errors.name">{{ form.errors.name }}</div>

        <!-- Description -->
        <label for="description">Description:</label>
        <input type="text" name="description" id="description" placeholder="Description" v-model="form.description" />
        <div v-if="form.errors.description">{{ form.errors.description }}</div>

        <!-- Genre -->
        <label for="genre">Genre:</label>
        <input list="genres" type="text" name="genrei" id="genre" v-on:keyup="debounceFn" autocomplete="off"
            v-model="form.genre" />
        <datalist id="genres">
            <option v-for="genre in genres" :key="genre.id" :value="genre.name">{{ capitalize(genre.name) }}</option>
        </datalist>
        <div v-if="form.errors.genre">{{ form.errors.genre }}</div>

        <!-- Developer -->
        <label for="developer">Developer:</label>
        <select name="developer" id="developer" v-model="form.developer"></select>
        <div v-if="form.errors.developer">{{ form.errors.developer }}</div>

        <!-- Publisher -->
        <label for="publisher">Publisher:</label>
        <select name="publisher" id="publisher" v-model="form.publisher"></select>
        <div v-if="form.errors.publisher">{{ form.errors.publisher }}</div>

        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</template>
