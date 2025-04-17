<script lang="ts" setup>
import { ref, type PropType } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3'
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import FormSection from '@/Components/Forms/FormSection.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import type { PreserveStateOption } from '@inertiajs/core';
import type { InertiaPageProps } from '@/Types/inertia';
import type { Person } from '@/Types';

const page = usePage<InertiaPageProps>();
const file = ref<HTMLInputElement>(null);

const { person } = defineProps({
    person: Object as PropType<Person>
});

const isBeingEdited: boolean = !!person;

const form = useForm<
    {
        name: string,
        description: string,
        type: any,
        image: File | null,
    }
>({
    name: person ? person.name : null,
    type: person ? person.type : null,
    image: person ? person.image : null,
    description: person ? person.description : null,
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

const submit = (): void => {
    form
        .post(route('persons.store'), {
            errorBag: 'createPerson',
            preserveScroll: true,
            preserveState: "errors.createPerson" as PreserveStateOption,
            onSuccess: (): void => { form.reset() },
            onError: (err: any): void => console.error(err)
        });
}
</script>

<template>
    <form-section title="Create Series" @on-submit="submit">
        <template #form>

            <!-- Name -->
            <input-label forHtml="title">Name</input-label>
            <form-input type="text" name="name" id="name" v-model="form.name" />
            <error-message v-if="page.props.errors.createSeries && page.props.errors.createSeries.name">{{
                page.props.errors.createSeries.name }}</error-message>
            <error-message v-if="page.props.errors.updateSeries && page.props.errors.updateSeries.name">{{
                page.props.errors.createSeries.name }}</error-message>

            <!-- Description -->
            <input-label forHtml="description">Description</input-label>
            <textarea rows="6" type="text" name="description" id="description" v-model="form.description" class="focus:border-hightlight focus:ring-highlight focus:ring-2 rounded shadow-sm
        bg-darkVariant/50 border-none" />
            <error-message v-if="page.props.errors.createSeries && page.props.errors.createSeries.description">{{
                page.props.errors.createSeries.description }}</error-message>
            <error-message v-if="page.props.errors.updateSeries && page.props.errors.updateSeries.description">{{
                page.props.errors.createSeries.description }}</error-message>

            <!-- Type -->
            <input-label forHtml="type">Author</input-label>
            <input type="checkbox" id="author" value="author" v-model="form.type" />

            <input-label forHtml="type">Narrator</input-label>
            <input type="checkbox" id="narrator" value="narrator" v-model="form.type" />

            <!-- Image -->
            <template v-if="!isBeingEdited">
                <input-label forHtml="image">Image</input-label>
                <input ref="file" type="file" name="image" id="image" @change="selectImage" accept="image/*" />
                <error-message v-if="page.props.errors.createBook && page.props.errors.createBook.image">{{
                    page.props.errors.createBook.image }}</error-message>
                <error-message v-if="form.errors && form.errors.image">{{
                    form.errors.image }}</error-message>
                <error-message v-if="page.props.errors.updateBook && page.props.errors.updateBook.image">{{
                    page.props.errors.updateBook.image }}</error-message>
            </template>
        </template>


        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                Save
            </PrimaryButton>
        </template>
    </form-section>
</template>
