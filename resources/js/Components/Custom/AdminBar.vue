<script lang="ts" setup>
import { computed, type PropType } from 'vue';
import { router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { isAdmin, canModerate } from '@/Utils';
import type { User } from '@/Types';

const { user, type, resource } = defineProps({
    user: Object as PropType<User>,
    resource: Object,
    type: String
});

const canUserModerate = computed<boolean>(() => canModerate(user));
const isUserAdmin = computed<boolean>(() => isAdmin(user));

const editResource = (): void => {
    router.get(route(`${type}.edit`, resource.id));
}

const deleteResource = (): void => {
    router.delete(route(`${type}.delete`, resource.id));
}

</script>

<template>
    <div class="flex justify-end gap-4">
        <primary-button v-if="canUserModerate" @click="editResource">
            Edit
        </primary-button>
        <danger-button v-if="isUserAdmin" @click="deleteResource">
            Delete
        </danger-button>
    </div>
</template>
