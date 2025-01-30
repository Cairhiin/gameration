<script lang="ts" setup>
import { computed, type PropType } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from '@/Components/Custom/DataTable.vue';
import AdminCreateSection from '@/Components/Custom/AdminCreateSection.vue';
import type { Publisher } from '@/Types';

const { publishers } = defineProps({
    publishers: Object as PropType<Publisher[]>
});

const showPublisher = (publisher_id: string): void => {
    router.get(route('publishers.show', publisher_id));
}
</script>

<template>
    <app-layout title="Publishers">
        <h2>Publishers</h2>
        <data-table :data="publishers" @show="showPublisher" />

        <!-- Admin Create Section -->
        <admin-create-section />
    </app-layout>
</template>
