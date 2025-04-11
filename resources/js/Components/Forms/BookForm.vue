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
import type { Book, Developer, Genre, Person, Publisher, Series } from '@/Types';

const page = usePage<InertiaPageProps>();
const file = ref<HTMLInputElement>(null);

const { book } = defineProps({
    book: Object as PropType<Book>
});

const form = useForm<
    {
        title: string,
        description: string,
        genres: Genre[],
        publisher: Publisher,
        published_at: string,
        pages: number,
        type: 'physical' | 'audiobook' | 'ebook',
        authors: Person[],
        narrators: Person[],
        series: Series,
        series_book_number: number,
        ISBN: string,
        time: string,
        image: File
    }
>({
    title: book ? book.title : null,
    description: book ? book.description : null,
    genres: book ? book.genres : [],
    publisher: book ? book.publisher : null,
    published_at: book ? book.published_at : null,
    pages: book ? book.pages : null,
    type: book ? book.type : 'physical',
    authors: book ? book.authors : [],
    narrators: book ? book.narrators : [],
    series: book ? book.series : null,
    series_book_number: book ? book.series_book_number : 1,
    ISBN: book ? book.ISBN : null,
    time: book ? book.time : '00:00',
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

const isBeingEdited: boolean = !!book

const getGenre = (genre: Genre): void => {
    form.genres.push(genre);
}

const getAuthor = (author: Person): void => {
    form.authors.push(author);
}

const getSeries = (series: Series): void => {
    form.series = series;
}

const getNarrator = (narrator: Person): void => {
    form.narrators.push(narrator);
}

const getPublisher = (publisher: Publisher): void => {
    form.publisher = publisher
}

const submit = (): void => {
    isBeingEdited ? form.transform((data) => ({
        ...data,
        genres: data.genres.map((genre: Genre) => genre.id),
        authors: data.authors.map((author: Person) => author.id),
        narrators: data.narrators.map((narrator: Person) => narrator.id),
        series: data.series ? data.series.id : null,
        publisher: data.publisher ? data.publisher.id : null,
    })).put(route('books.update', book.id), {
        errorBag: 'updateBook',
        preserveScroll: true,
        preserveState: "errors.updateBook" as PreserveStateOption,
        onSuccess: (): void => { form.reset() },
        onError: (err: any): void => console.log(err)
    }) : form.transform((data) => ({
        ...data,
        genres: data.genres.map((genre: Genre) => genre.id),
        authors: data.authors.map((author: Person) => author.id),
        narrators: data.narrators.map((narrator: Person) => narrator.id),
        series: data.series ? data.series.id : null,
        publisher: data.publisher ? data.publisher.id : null,
    })).post(route('books.store'), {
        errorBag: 'createBook',
        preserveScroll: true,
        preserveState: "errors.createBook" as PreserveStateOption,
        onSuccess: (): void => { form.reset() },
        onError: (err: any): void => console.log(err)
    });
}
</script>

<template>
    <form-section :title="`${isBeingEdited ? 'Edit' : 'Create'} Book`" @on-submit="submit">
        <template #form>

            <!-- Name -->
            <input-label forHtml="title">Title</input-label>
            <form-input type="text" name="title" id="title" v-model="form.title" />
            <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.title">{{
                page.props.errors.createBook.title }}</error-message>

            <!-- Description -->
            <input-label forHtml="description">Description</input-label>
            <textarea rows="6" type="text" name="description" id="description" v-model="form.description" class="focus:border-hightlight focus:ring-highlight focus:ring-2 rounded shadow-sm
        bg-darkVariant/50 border-none" />
            <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.description">{{
                page.props.errors.createBook.description }}</error-message>
            <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.description">{{
                page.props.errors.updateBook.description }}</error-message>

            <!-- Type -->
            <input-label forHtml="type">Type</input-label>
            <select name="type" id="type" v-model="form.type" class="focus:border-hightlight focus:ring-highlight focus:ring-2 rounded shadow-sm
        bg-darkVariant/50 border-none">
                <option value="physical" selected>Physical</option>
                <option value="audiobook">Audiobook</option>
                <option value="ebook">Ebook</option>
            </select>

            <!-- Pages -->
            <template v-if="form.type !== 'audiobook'">
                <input-label forHtml="pages">Pages</input-label>
                <form-input type="number" name="pages" id="pages" v-model="form.pages" />
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.pages">{{
                    page.props.errors.createBook.pages }}</error-message>
            </template>

            <!-- Running Time -->
            <template v-if="form.type === 'audiobook'">
                <input-label forHtml="time">Running Time</input-label>
                <form-input type="string" name="time" id="time" v-model="form.time" placeholder="hh:mm" />
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.time">{{
                    page.props.errors.createBook.time }}</error-message>
            </template>

            <!-- Series -->
            <input-label forHtml="series">Series</input-label>
            <search-input search-type="books.series" :multi-select="false" :value="form.series"
                @update:model-value="getSeries"></search-input>
            <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.series">{{
                page.props.errors.createBook.series }}</error-message>

            <!-- Series Book Number -->
            <template v-if="form.series">
                <input-label forHtml="series_book_number">Series Book Number</input-label>
                <form-input type="number" name="series_book_number" id="series_book_number"
                    v-model="form.series_book_number" />
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.series_book_number">{{
                    page.props.errors.createBook.series_book_number }}</error-message>
                <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.series_book_number">{{
                    page.props.errors.updateBook.series_book_number }}</error-message>
            </template>

            <!-- Authors -->
            <input-label forHtml="authors">Authors</input-label>
            <search-input search-type="books.authors" :multi-select="true" :value="form.authors"
                @update:model-value="getAuthor"></search-input>
            <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.authors">{{
                page.props.errors.createBook.authors }}</error-message>

            <!-- Narrators -->
            <template v-if="form.type === 'audiobook'">
                <input-label forHtml="narrators">Narrators</input-label>
                <search-input search-type="books.narrators" :multi-select="true" :value="form.narrators"
                    @update:model-value="getNarrator"></search-input>
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.narrators">{{
                    page.props.errors.createBook.narrators }}</error-message>
            </template>

            <!-- Genres -->
            <input-label forHtml="genres">Genres</input-label>
            <search-input search-type="genres" :multi-select="true" :value="form.genres"
                @update:model-value="getGenre"></search-input>
            <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.genre">{{
                page.props.errors.createBook.genre }}</error-message>

            <!-- Publisher -->
            <input-label forHtml="publisher">Publisher</input-label>
            <search-input search-type="publishers" @update:model-value="getPublisher"
                :value="form.publisher"></search-input>
            <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.publisher">{{
                page.props.errors.createBook.publisher }}</error-message>

            <!-- Publishing Date -->
            <input-label forHtml="released">Publishing Date</input-label>
            <form-input type="date" name="released" id="released" v-model="form.published_at" />
            <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.published_at">{{
                page.props.errors.createBook.published_at }}</error-message>

            <!-- ISBN -->
            <input-label forHtml="isbn">ISBN</input-label>
            <form-input type="text" name="isbn" id="isbn" v-model="form.ISBN" />
            <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.isbn">{{
                page.props.errors.createBook.isbn }}</error-message>

            <!-- Image -->
            <template v-if="!isBeingEdited">
                <input-label forHtml="image">Image</input-label>
                <input ref="file" type="file" name="image" id="image" @change="selectImage" accept="image/*" />
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.image">{{
                    page.props.errors.createBook.image }}</error-message>
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
