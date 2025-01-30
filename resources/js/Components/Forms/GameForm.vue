<script lang="ts" setup>
import { ref, type PropType } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3'
import SearchInput from '@/Components/Forms/SearchInput.vue';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import FormSection from '@/Components/Forms/FormSection.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import type { PreserveStateOption } from '@inertiajs/core';
import type { InertiaPageProps } from '@/Types/inertia';
import type { Developer, Game, Genre, Publisher } from '@/Types';

const page = usePage<InertiaPageProps>();
const file = ref<HTMLInputElement>(null);

const { game } = defineProps({
    game: Object as PropType<Game>
});

const form = useForm<
    {
        name: string,
        description: string,
        genres: Genre[],
        developer: Developer,
        publisher: Publisher,
        released: string,
        image: File
    }
>({
    name: game ? game.name : '',
    description: game ? game.description : '',
    genres: game ? game.genres : null,
    developer: game ? game.developer : null,
    publisher: game ? game.publisher : null,
    released: game ? game.released_at : '',
    image: null
});

const selectImage = (): void => {
    form.clearErrors('image');
    let myFile = file.value.files.length ? file.value.files[0] : null;

    if (myFile && myFile.size < 2 * 1024 * 1024) {
        form.image = myFile
    } else {
        form.errors.image = "Image must be less than 2MB"
    }
};

const isBeingEdited: boolean = !!game

const getGenre = (genre: Genre): void => {
    form.genres.push(genre);
}

const getDeveloper = (developer: Developer): void => {
    form.developer = developer
}

const getPublisher = (publisher: Publisher): void => {
    form.publisher = publisher
}

const submit = (): void => {
    isBeingEdited ? form.put(route('games.update', game.id), {
        errorBag: 'updateGame',
        preserveScroll: true,
        preserveState: "errors.updateGame" as PreserveStateOption,
        onSuccess: (): void => { form.reset() },
        onError: (err: any): void => console.log(err)
    }) : form.post(route('games.store'), {
        errorBag: 'createGame',
        preserveScroll: true,
        preserveState: "errors.createGame" as PreserveStateOption,
        onSuccess: (): void => { form.reset() },
        onError: (err: any): void => console.log(err)
    });
}
</script>

<template>
    <form-section :title="`${isBeingEdited ? 'Edit' : 'Create'} Game`" @on-submit="submit">
        <template #form>

            <!-- Name -->
            <input-label forHtml="name">Name</input-label>
            <form-input type="text" name="name" id="name" v-model="form.name" />
            <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.name">{{
                page.props.errors.createGame.name }}</error-message>

            <!-- Description -->
            <input-label forHtml="description">Description</input-label>
            <textarea rows="6" type="text" name="description" id="description" v-model="form.description" class="focus:border-hightlight focus:ring-highlight focus:ring-2 rounded shadow-sm
        bg-darkVariant/50 border-none" />
            <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.description">{{
                page.props.errors.createGame.description }}</error-message>
            <error-message v-if="page.props.errors.updateGame && page.props.errors.updateGame.description">{{
                page.props.errors.updateGame.description }}</error-message>

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
            <template v-if="!isBeingEdited">
                <input-label forHtml="image">Image</input-label>
                <input ref="file" type="file" name="image" id="image" @change="selectImage" accept="image/*" />
                <error-message v-if="page.props.errors.createGame && page.props.errors.createGame.image">{{
                    page.props.errors.createGame.image }}</error-message>
                <error-message v-if="form.errors && form.errors.image">{{
                    form.errors.image }}</error-message>
            </template>
        </template>

        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                Save
            </PrimaryButton>
        </template>
    </form-section>
</template>
