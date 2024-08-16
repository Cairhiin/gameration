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

const role = computed(() => user?.role.name.toLowerCase());

const editResource = () => {
    router.get(route(`${type}.edit`, resource.id));
}

const deleteResource = () => {
    router.delete(route(`${type}.delete`, resource.id));
}

</script>

<template>
    <div class="flex justify-end gap-4">
        <primary-button v-if="(role === 'moderator' && user.id === resource?.user_id) || role === 'admin'"
            @click="editResource">
            Edit
        </primary-button>
        <danger-button v-if="role === 'admin'" @click="deleteResource">
            Delete
        </danger-button>
    </div>
</template>
