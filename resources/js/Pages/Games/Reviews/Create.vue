<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import type { Game } from '@/Types';
import type { PropType } from 'vue';

defineProps({
    game: Object as PropType<Game>
});
</script>

<template>
    <AppLayout title="Create Review">
        <h2>Create Review</h2>
    </AppLayout>
</template>
