<script setup>
import { router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    hasModerationRights: {
        type: Boolean,
        default: false
    }
});

const addGame = () => {
    router.get(route('games.create'));
}

const addDeveloper = () => {
    router.get(route('developers.create'));
}

const addPublisher = () => {
    router.get(route('publishers.create'));
}
</script>

<template>
    <aside class="flex gap-4 justify-end my-4" v-if="hasModerationRights">
        <primary-button @click="addGame">Add Game</primary-button>
        <primary-button @click="addDeveloper">Add Developer</primary-button>
        <primary-button @click="addPublisher">Add Publisher</primary-button>
    </aside>
</template>
