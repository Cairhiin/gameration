<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3'
import SearchInput from '@/Components/Forms/SearchInput.vue';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import PrimaryButton from '../Custom/PrimaryButton.vue';

const page = usePage();
const file = ref(null);

const { game } = defineProps({
    game: Object
});

const selectImage = () => {
    form.clearErrors('image');
    let myFile = file.value.files.length ? file.value.files[0] : null;

    if (myFile && myFile.size < 2 * 1024 * 1024) {
        form.image = myFile
    } else {
        form.errors.image = "Image must be less than 2MB"
    }
};

const isBeingEdited = !!game

const form = useForm({
    name: game ? game.name : null,
    description: game ? game.description : null,
    genres: game ? game.genres : null,
    developer: game ? game.developer : null,
    publisher: game ? game.publisher : null,
    released: game ? game.released_at : null,
    image: game ? game.image : null
});

const getGenre = (result) => {
    form.genres = result
}

const getDeveloper = (result) => {
    form.developer = result
}

const getPublisher = (result) => {
    form.publisher = result
}

const submit = () => {
    isBeingEdited ? form.put(route('games.update', game.id), {
        errorBag: 'updateGame',
        preserveScroll: true,
        preserveState: "errors.updateGame",
        onSuccess: () => form.reset()
    }) : form.post(route('games.store'), {
        errorBag: 'createGame',
        preserveScroll: true,
        preserveState: "errors.createGame",
        onSuccess: () => form.reset()
    });
}
</script>

<template>
    <form class="flex flex-col m-8 max-w-xl gap-4 mx-auto bg-highlight/25 shadow-md rounded-lg p-8"
        @submit.prevent="submit">
        <h2 class="text-center font-bold uppercase text-xl text-lightVariant">Create game</h2>

        <!-- Name -->
        <input-label forHtml="name">Name</input-label>
        <form-input type="text" name="name" id="name" v-model="form.name" />
        <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.name">{{
            page.props.errors.createGame.name }}</error-message>

        <!-- Description -->
        <input-label forHtml="description">Description</input-label>
        <textarea rows="6" type="text" name="description" id="description" v-model="form.description" class="focus:border-hightlight focus:ring-highlight focus:ring-2 rounded shadow-sm
        bg-dark border-none" />
        <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.description">{{
            page.props.errors.createGame.description }}</error-message>

        <!-- Genre -->
        <input-label forHtml="genre">Genre</input-label>
        <search-input search-type="genres" :multi-select="true" :value="form.genres"
            @update:model-value="getGenre"></search-input>
        <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.genre">{{
            page.props.errors.createGame.genre }}</error-message>

        <!-- Developer -->
        <input-label forHtml="developer">Developer</input-label>
        <search-input search-type="developers" @update:model-value="getDeveloper"
            :value="form.developer"></search-input>
        <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.developer">{{
            page.props.errors.createGame.developer }}</error-message>

        <!-- Publisher -->
        <input-label forHtml="publisher">Publisher</input-label>
        <search-input search-type="publishers" @update:model-value="getPublisher"
            :value="form.publisher"></search-input>
        <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.publisher">{{
            page.props.errors.createGame.publisher }}</error-message>

        <!-- Release Date -->
        <input-label forHtml="released">Release Date</input-label>
        <form-input type="date" name="released" id="released" v-model="form.released" />
        <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.released">{{
            page.props.errors.createGame.released }}</error-message>

        <!-- Image -->
        <input-label forHtml="image">Image</input-label>
        <form-input ref="file" type="file" name="image" id="image" @change="selectImage" accept="image/*" />
        <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.image">{{
            page.props.errors.createGame.image }}</error-message>
        <error-message v-if="form.errors && form.errors.image">{{
            form.errors.image }}</error-message>

        <primary-button type="submit" size="text-default" class="mt-8">{{
            !isBeingEdited ? "Create" : "Update" }}</primary-button>
    </form>
</template>
