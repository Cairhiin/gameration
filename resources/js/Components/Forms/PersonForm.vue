<script lang="ts" setup>
import { ref, type PropType } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3'
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import FormSection from '@/Components/Forms/FormSection.vue';
import SecondaryButton from '@/Components/Custom/SecondaryButton.vue';
import TiptapEditor from '@/Components/Custom/TipTap/TipTapEditor.vue';
import RadioButton from '@/Components/Custom/RadioButton.vue';
import FileUpload from '@/Components/Custom/FileUpload.vue';
import FormStepper from '@/Components/Custom/FormStepper.vue';
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
        type: "narrator" | "author" | "both",
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

const currentStep = ref<number>(0);

const setActiveStep = (step: number): void => {
    currentStep.value = step;
};
</script>

<template>
    <form-section title="Create Person" @on-submit="submit">
        <template #form>
            <form-stepper :activeStep="currentStep" :steps="[
                { title: 'Person Details' },
                { title: 'Image' }
            ]" @step-changed="currentStep = $event" />

            <fieldset class="flex flex-col m-8 gap-4" v-if="currentStep === 0">
                <!--Name -->
                <input-label forHtml=" title">Name</input-label>
                <form-input type="text" name="name" id="name" title="Name" v-model="form.name" />
                <error-message v-if="page.props.errors.createSeries && page.props.errors.createSeries.name">{{
                    page.props.errors.createSeries.name }}</error-message>
                <error-message v-if="page.props.errors.updateSeries && page.props.errors.updateSeries.name">{{
                    page.props.errors.createSeries.name }}</error-message>

                <!-- Description -->
                <input-label forHtml="description">Description</input-label>
                <tiptap-editor v-model="form.description" name="description" id="description" title="Description" />
                <error-message v-if="page.props.errors.createSeries && page.props.errors.createSeries.description">{{
                    page.props.errors.createSeries.description }}</error-message>
                <error-message v-if="page.props.errors.updateSeries && page.props.errors.updateSeries.description">{{
                    page.props.errors.createSeries.description }}</error-message>

                <!-- Type -->
                <radio-button name="type" title="Type"
                    :options="[{ value: 'narrator', label: 'Narrator' }, { value: 'author', label: 'Author' }, { value: 'both', label: 'Both' }]"
                    v-model="form.type" />
            </fieldset>

            <!-- Image -->
            <fieldset class="flex flex-col m-8 gap-4" v-if="currentStep === 1">
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
                        v-if="currentStep === 1">
                        <div class="flex gap-2 items-center"><i class="fa fa-solid fa-chevron-left"></i>
                            <div>Previous</div>
                        </div>
                    </SecondaryButton>
                    <SecondaryButton type="button" @click="setActiveStep(currentStep + 1)" variant="outline"
                        v-if="currentStep === 0">
                        <div class="flex gap-2 items-center">
                            <div>Next</div> <i class="fa fa-solid fa-chevron-right"></i>
                        </div>
                    </SecondaryButton>
                </div>
                <SecondaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit"
                    v-if="currentStep === 1">
                    Save
                </SecondaryButton>
            </div>
        </template>
    </form-section>
</template>
