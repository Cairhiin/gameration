<script lang="ts" setup>
import { ref, computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3'
import InputLabel from '@/Components/Custom/InputLabel.vue';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import FormSection from '@/Components/Forms/FormSection.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import defaultImage from '../../../../images/missing_image_light.png';
import type { PropType } from 'vue';
import type { Game } from '@/Types';
import type { InertiaPageProps } from '@/Types/inertia';

const { game } = defineProps({
    game: Object as PropType<Game>
});

const image = ref<HTMLInputElement>(null);
const imagePreview = ref<string>(null);

const gameImage = computed<string>(() => {
    if (imagePreview.value) {
        return imagePreview.value;
    }

    return game?.image ? `/storage/${game?.image}` : defaultImage;
});

const { props } = usePage<InertiaPageProps>();
const form = useForm<{ image: File }>({
    image: null,
});

const submit = (): void => {
    // Not using the form helper to spoof the put method to upload the image
    router.post(route('games.image.update', game.id), {
        _method: 'put',
        ...form as any,
    });
}

const selectImage = (): void => {
    form.clearErrors('image');
    let myFile: File | null = image.value.files.length ? image.value.files[0] : null;

    if (myFile && myFile.size < 2 * 1024 * 1024) {
        form.image = myFile

        const reader = new FileReader;
        reader.onload = e => {
            imagePreview.value = e.target.result.toString();
        }

        reader.readAsDataURL(myFile);
    } else {
        form.errors.image = "Image must be less than 2MB"
    }
};
</script>

<template>
    <AppLayout title="Edit Image">
        <form-section title="Edit Image" @on-submit="submit">
            <template #form>
                <div class="flex justify-center">
                    <img :src="gameImage" :alt="game?.name" class="object-cover">
                </div>
                <input-label forHtml="image">Image</input-label>
                <input ref="image" type="file" name="image" id="image" @change="selectImage" accept="image/*" />
                <error-message v-if="props.errors.updateImage && props.errors.updateImage.image">{{
                    props.errors.updateImage.image }}</error-message>
                <error-message v-if="form.errors && form.errors.image">{{
                    form.errors.image }}</error-message>
            </template>

            <template #actions>
                <primary-button type="submit">Save</primary-button>
            </template>
        </form-section>
    </AppLayout>
</template>
