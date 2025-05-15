<script lang="ts" setup>
import { ref, type PropType } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3'
import SearchInput from '@/Components/Forms/SearchInput.vue';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import FormSection from '@/Components/Forms/FormSection.vue';
import TipTapEditor from '@/Components/Custom/TipTap/TipTapEditor.vue';
import RadioButton from '@/Components/Custom/RadioButton.vue';
import FormStepper from '@/Components/Custom/FormStepper.vue';
import SecondaryButton from '@/Components/Custom/SecondaryButton.vue';
import FileUpload from '@/Components/Custom/FileUpload.vue';
import type { PreserveStateOption } from '@inertiajs/core';
import type { InertiaPageProps } from '@/Types/inertia';
import type { Book, Genre, Person, Publisher, Series } from '@/Types';

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
    pages: book ? book.pages : 0,
    type: book ? book.type : 'physical',
    authors: book ? book.authors : [],
    narrators: book ? book.narrators : [],
    series: book ? book.series : null,
    series_book_number: book ? book.series_book_number : null,
    ISBN: book ? book.ISBN : null,
    time: book ? book.time : '00:00',
    image: null
});

const selectImage = (file: File): void => {
    form.clearErrors('image');

    if (!file) {
        form.image = null;
        return;
    }

    if (file.size < 2 * 1024 * 1024) {
        form.image = file
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
    form.transform((data) => ({
        ...data,
        genres: data.genres.map((genre: Genre) => genre.id),
        authors: data.authors.map((author: Person) => author.id),
        narrators: data.narrators.map((narrator: Person) => narrator.id),
        series: data.series ? data.series.id : null,
        publisher: data.publisher ? data.publisher.id : null,
    }));

    if (isBeingEdited && book && book.id) {
        form.put(route('books.update', book.id), {
            errorBag: 'updateBook',
            preserveScroll: true,
            preserveState: "errors.updateBook" as PreserveStateOption,
            onSuccess: (): void => { form.reset() },
            onError: (err: any): void => console.error(err)
        });
    } else {
        form.post(route('books.store'), {
            errorBag: 'createBook',
            preserveScroll: true,
            preserveState: "errors.createBook" as PreserveStateOption,
            onSuccess: (): void => { form.reset() },
            onError: (err: any): void => console.error(err)
        });
    }
}

const currentStep = ref<number>(0);

const setActiveStep = (step: number): void => {
    currentStep.value = step;
};
</script>

<template>
    <form-section :title="`${isBeingEdited ? 'Edit' : 'Create'} Book`" @on-submit="submit">
        <template #form>
            <form-stepper :activeStep="currentStep" :steps="[
                { title: 'Book Details' },
                { title: 'Additional Details' },
                { title: 'Image' }
            ]" @step-changed="currentStep = $event" />

            <fieldset class="flex flex-col m-8 gap-4" v-if="currentStep === 0">
                <!-- Name -->
                <input-label forHtml="title">Title</input-label>
                <form-input type="text" name="title" id="title" v-model="form.title" />
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.title">{{
                    page.props.errors.createBook.title }}</error-message>
                <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.title">{{
                    page.props.errors.updateBook.title }}</error-message>

                <!-- Description -->
                <input-label forHtml="description">Description</input-label>
                <!-- <textarea rows="6" type="text" name="description" id="description" v-model="form.description" class="focus:border-hightlight focus:ring-highlight focus:ring-2 rounded shadow-sm
        bg-darkVariant/50 border-none" /> -->
                <TipTapEditor v-model="form.description" />
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.description">{{
                    page.props.errors.createBook.description }}</error-message>
                <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.description">{{
                    page.props.errors.updateBook.description }}</error-message>

                <!-- Series -->
                <input-label forHtml="series">Series</input-label>
                <search-input search-type="books.series" :multi-select="false" :value="form.series"
                    @update:model-value="getSeries"></search-input>
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.series">{{
                    page.props.errors.createBook.series }}</error-message>
                <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.series">{{
                    page.props.errors.updateBook.series }}</error-message>

                <!-- Series Book Number -->
                <template v-if="form.series">
                    <input-label forHtml="series_book_number">Series Book Number</input-label>
                    <form-input type="number" name="series_book_number" id="series_book_number"
                        v-model="form.series_book_number" />
                    <error-message
                        v-if="page.props.errors.createBook && page.props.errors.createBook.series_book_number">{{
                            page.props.errors.createBook.series_book_number }}</error-message>
                    <error-message
                        v-if="page.props.errors.updateBook && page.props.errors.updateBook.series_book_number">{{
                            page.props.errors.updateBook.series_book_number }}</error-message>
                </template>

                <!-- Authors -->
                <input-label forHtml="authors">Authors</input-label>
                <search-input search-type="books.authors" :multi-select="true" :value="form.authors"
                    @update:model-value="getAuthor"></search-input>
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.authors">{{
                    page.props.errors.createBook.authors }}</error-message>
                <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.authors">{{
                    page.props.errors.updateBook.authors }}</error-message>

                <!-- Genres -->
                <input-label forHtml="genres">Genres</input-label>
                <search-input search-type="genres" :multi-select="true" :value="form.genres" type="book"
                    @update:model-value="getGenre"></search-input>
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.genre">{{
                    page.props.errors.createBook.genre }}</error-message>
                <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.genre">{{
                    page.props.errors.updateBook.genre }}</error-message>
            </fieldset>

            <fieldset class="flex flex-col m-8 gap-4" v-if="currentStep === 1">
                <!-- Type -->
                <radio-button label="Type"
                    :options="[{ value: 'physical', label: 'Physical' }, { value: 'ebook', label: 'Ebook' }, { value: 'audiobook', label: 'Audiobook' }]"
                    v-model="form.type" title="Type" />

                <!-- Pages -->
                <template v-if="form.type !== 'audiobook'">
                    <input-label forHtml="pages">Pages</input-label>
                    <form-input type="number" name="pages" id="pages" v-model="form.pages" />
                    <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.pages">{{
                        page.props.errors.createBook.pages }}</error-message>
                    <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.pages">{{
                        page.props.errors.updateBook.pages }}</error-message>
                </template>

                <!-- Running Time -->
                <template v-if="form.type === 'audiobook'">
                    <input-label forHtml="time">Running Time</input-label>
                    <form-input type="string" name="time" id="time" v-model="form.time" placeholder="hh:mm" />
                    <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.time">{{
                        page.props.errors.createBook.time }}</error-message>
                    <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.time">{{
                        page.props.errors.updateBook.time }}</error-message>
                </template>

                <!-- Narrators -->
                <template v-if="form.type === 'audiobook'">
                    <input-label forHtml="narrators">Narrators</input-label>
                    <search-input search-type="books.narrators" :multi-select="true" :value="form.narrators"
                        @update:model-value="getNarrator"></search-input>
                    <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.narrators">{{
                        page.props.errors.createBook.narrators }}</error-message>
                    <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.narrators">{{
                        page.props.errors.updateBook.narrators }}</error-message>
                </template>

                <!-- Publisher -->
                <input-label forHtml="publisher">Publisher</input-label>
                <search-input search-type="publishers" @update:model-value="getPublisher"
                    :value="form.publisher"></search-input>
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.publisher">{{
                    page.props.errors.createBook.publisher }}</error-message>
                <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.publisher">{{
                    page.props.errors.updateBook.publisher }}</error-message>

                <!-- Publishing Date -->
                <input-label forHtml="released">Publishing Date</input-label>
                <form-input type="date" name="released" id="released" v-model="form.published_at" />
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.published_at">{{
                    page.props.errors.createBook.published_at }}</error-message>
                <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.published_at">{{
                    page.props.errors.updateBook.published_at }}</error-message>

                <!-- ISBN -->
                <input-label forHtml="isbn">ISBN</input-label>
                <form-input type="text" name="isbn" id="isbn" v-model="form.ISBN" />
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.isbn">{{
                    page.props.errors.createBook.isbn }}</error-message>
                <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.isbn">{{
                    page.props.errors.updateBook.isbn }}</error-message>
            </fieldset>

            <fieldset class="flex flex-col m-8 gap-4" v-if="currentStep === 2">
                <!-- Image -->
                <template v-if="!isBeingEdited">
                    <file-upload @input="selectImage" />
                    <div ref="file" v-if="form.image">Image: {{ form.image.name }}</div>
                    <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.image">{{
                        page.props.errors.createBook.image }}</error-message>
                    <error-message v-if="form.errors && form.errors.image">{{
                        form.errors.image }}</error-message>
                    <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.image">{{
                        page.props.errors.updateBook.image }}</error-message>
                </template>
            </fieldset>
        </template>

        <template #actions>
            <div class="flex gap-2 justify-between">
                <div class="flex gap-2 justify-start">
                    <SecondaryButton type="button" @click="setActiveStep(currentStep - 1)" variant="outline"
                        v-if="currentStep > 0 && currentStep < 3">
                        <div class="flex gap-2 items-center"><i class="fa fa-solid fa-chevron-left"></i>
                            <div>Previous</div>
                        </div>
                    </SecondaryButton>
                    <SecondaryButton type="button" @click="setActiveStep(currentStep + 1)" variant="outline"
                        v-if="currentStep < 2">
                        <div class="flex gap-2 items-center">
                            <div>Next</div> <i class="fa fa-solid fa-chevron-right"></i>
                        </div>
                    </SecondaryButton>
                </div>
                <SecondaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit"
                    v-if="currentStep === 2">
                    Save
                </SecondaryButton>
            </div>
        </template>
    </form-section>
</template>
