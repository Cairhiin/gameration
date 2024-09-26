<script setup>
import { router, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const { resource, type } = defineProps({
    resource: Object,
    type: String
});

const page = usePage();
const hasModerationRights = page.props.auth.user.role.name === 'Admin' ||
    (page.props.auth.user.role.name === 'Moderator' && resource.user_id === page.props.auth.user.id);
const isAdmin = page.props.auth.user.role.name === 'Admin';

const editResource = () => router.get(route(`${type}s.edit`, resource));

const deleteResource = () => router.delete(route(`${type}.delete`, resource));
</script>

<template>
    <aside class="flex gap-4 justify-end my-4">
        <primary-button class="w-32" v-if="hasModerationRights" @click="editResource">Edit</primary-button>
        <danger-button class="w-32" v-if="isAdmin" @click="deleteResource">Delete</danger-button>
    </aside>
</template>
