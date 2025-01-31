<script lang="ts" setup>
import { computed, type PropType } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import type { InertiaPageProps } from '@/Types/inertia';
import { isAdmin, canModerate } from '@/Utils';
import type { User } from '@/Types';

const page = usePage<InertiaPageProps>();
const { user } = defineProps({
    user:
    {
        type: Object as PropType<User>,
        default: null
    }
});
const hasModerationRights = computed<boolean>(() => canModerate(user ?? page.props.auth.user));
const isUserAdmin = computed<boolean>(() => isAdmin(user ?? page.props.auth.user));

const add = (type: string): void => {
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
