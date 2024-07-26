<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import SearchInput from '@/Components/Forms/SearchInput.vue';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';

const page = usePage();

const form = useForm({
    name: null,
    description: null,
    genre: null,
    developer: null,
    publisher: null,
    released: null
});

const getGenre = (result) => {
    form.genre = result
}

const getDeveloper = (result) => {
    form.developer = result
}

const getPublisher = (result) => {
    form.publisher = result
}

const submit = () => {
    form.post(route('games.store'), {
        errorBag: 'createGame',
        preserveScroll: true,
        preserveState: "errors.createGame",
        onSuccess: () => form.reset()
    })
}
</script>

<template>
    <form class="flex flex-col m-8 max-w-xl gap-4 mx-auto bg-black shadow-md rounded-lg p-8 text-slate-500"
        @submit.prevent="form.post(route('games.store'))">

        <!-- Name -->
        <label for="name">Name</label>
        <input type="text" name="name" id="name" placeholder="Name" v-model="form.name" />
        <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.name">{{
            page.props.errors.createGame.name }}</error-message>

        <!-- Description -->
        <label for="description">Description</label>
        <textarea type="text" name="description" id="description" placeholder="Description"
            v-model="form.description" />
        <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.description">{{
            page.props.errors.createGame.description }}</error-message>

        <!-- Genre -->
        <label for="genre">Genre</label>
        <search-input search-type="genres" @update:model-value="getGenre"></search-input>
        <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.genre">{{
            page.props.errors.createGame.genre }}</error-message>

        <!-- Developer -->
        <label for="developer">Developer:</label>
        <search-input search-type="developers" @update:model-value="getDeveloper"></search-input>
        <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.developer">{{
            page.props.errors.createGame.developer }}</error-message>

        <!-- Publisher -->
        <label for="publisher">Publisher</label>
        <search-input search-type="publishers" @update:model-value="getPublisher"></search-input>
        <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.publisher">{{
            page.props.errors.createGame.publisher }}</error-message>

        <!-- Release Date -->
        <label for="released">Release Date</label>
        <input type="date" name="released" id="released" v-model="form.released" />
        <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.released">{{
            page.props.errors.createGame.released }}</error-message>

        <button type="submit" @click="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</template>
