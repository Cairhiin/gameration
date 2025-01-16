<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const { user, type, resource } = defineProps({
    user: Object,
    resource: Object,
    type: String
});

const canModerate = computed(() => user?.roles.filter(role => role.name.includes('moderator')).length > 0
    || user?.roles.filter(role => role.name.includes('admin')).length > 0);
const isAdmin = computed(() => user?.roles.filter(role => role.name.includes('admin')).length > 0);

const editResource = () => {
    router.get(route(`${type}.edit`, resource.id));
}

const deleteResource = () => {
    router.delete(route(`${type}.delete`, resource.id));
}

</script>

<template>
    <div class="flex justify-end gap-4">
        <primary-button v-if="canModerate" @click="editResource">
            Edit
        </primary-button>
        <danger-button v-if="isAdmin" @click="deleteResource">
            Delete
        </danger-button>
    </div>
</template>
