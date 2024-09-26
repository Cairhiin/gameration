<script setup>
import { router, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const page = usePage();
const hasModerationRights = page.props.auth.user.role.name === 'Admin' || page.props.auth.user.role.name === 'Moderator';
const isAdmin = page.props.auth.user.role.name === 'Admin';

const add = (type) => {
    router.get(route(`${type}s.create`));
}
</script>

<template>
    <aside class="flex gap-4 justify-end my-4" v-if="hasModerationRights">
        <primary-button @click="add('game')">Add Game</primary-button>
        <primary-button @click="add('developer')">Add Developer</primary-button>
        <primary-button @click="add('publisher')">Add Publisher</primary-button>
        <primary-button @click="add('genre')" v-if="isAdmin">Add Genre</primary-button>
    </aside>
</template>
