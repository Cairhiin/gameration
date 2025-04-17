<script lang="ts" setup>
import { type PropType } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3'
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import FormSection from '@/Components/Forms/FormSection.vue';
import SearchInput from '@/Components/Forms/SearchInput.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import type { PreserveStateOption } from '@inertiajs/core';
import type { InertiaPageProps } from '@/Types/inertia';
import type { Person, Series } from '@/Types';

const page = usePage<InertiaPageProps>();

const { series } = defineProps({
    series: Object as PropType<Series>
});

const form = useForm<
    {
        title: string,
        description: string,
        authors: Person[],
    }
>({
    title: series ? series.title : null,
    description: series ? series.description : null,
    authors: series ? series.authors : [],
});

const getAuthor = (author: Person): void => {
    form.authors.push(author);
}

const submit = (): void => {
    form
        .transform((data) => ({ ...data, authors: data.authors.map((author: Person) => author.id) }))
        .post(route('books.series.store'), {
            errorBag: 'createSeries',
            preserveScroll: true,
            preserveState: "errors.createSeries" as PreserveStateOption,
            onSuccess: (): void => { form.reset() },
            onError: (err: any): void => console.error(err)
        });
}
</script>

<template>
    <form-section title="Create Series" @on-submit="submit">
        <template #form>

            <!-- Name -->
            <input-label forHtml="title">Title</input-label>
            <form-input type="text" name="title" id="title" v-model="form.title" />
            <error-message v-if="page.props.errors.createSeries && page.props.errors.createSeries.title">{{
                page.props.errors.createSeries.title }}</error-message>
            <error-message v-if="page.props.errors.updateSeries && page.props.errors.updateSeries.title">{{
                page.props.errors.createSeries.title }}</error-message>

            <!-- Description -->
            <input-label forHtml="description">Description</input-label>
            <textarea rows="6" type="text" name="description" id="description" v-model="form.description" class="focus:border-hightlight focus:ring-highlight focus:ring-2 rounded shadow-sm
        bg-darkVariant/50 border-none" />
            <error-message v-if="page.props.errors.createSeries && page.props.errors.createSeries.description">{{
                page.props.errors.createSeries.description }}</error-message>
            <error-message v-if="page.props.errors.updateSeries && page.props.errors.updateSeries.description">{{
                page.props.errors.createSeries.description }}</error-message>

            <!-- Authors -->
            <input-label forHtml="authors">Authors</input-label>
            <search-input search-type="books.authors" :multi-select="true" :value="form.authors"
                @update:model-value="getAuthor"></search-input>
            <error-message v-if="page.props.errors.createSeries && page.props.errors.createSeries.authors">{{
                page.props.errors.createSeries.authors }}</error-message>
            <error-message v-if="page.props.errors.updateSeries && page.props.errors.updateSeries.authors">{{
                page.props.errors.createSeries.authors }}</error-message>
        </template>


        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                Save
            </PrimaryButton>
        </template>
    </form-section>
</template>
