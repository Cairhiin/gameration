<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from '@/Components/Custom/DataTable.vue';
import AdminCreateSection from '@/Components/Custom/AdminCreateSection.vue';

const { publishers } = defineProps({
    publishers: Array
});

const showPublisher = (publisher) => {
    if (!publisher.id) {
        const publisherClicked = publishers.filter((p) => p.name === publisher.name);
        publisher.id = publisherClicked.length > 0 ? publisherClicked[0].id : null;
    }
    router.get(route('publishers.show', publisher.id));
}

const formattedPublishersForTable = computed(() => {
    return publishers.map((publisher) => {
        return {
            name: publisher.name,
            avg_rating: publisher.avg_rating?.toFixed(1),
            games_count: publisher.games_count,
        };
    });
});
</script>

<template>
    <app-layout title="Publishers">
        <h2>Publishers</h2>
        <data-table :data="formattedPublishersForTable" :headers="['Genre', 'Avg Rating', 'Games Count']"
            @show="showPublisher" />

        <!-- Admin Create Section -->
        <admin-create-section />
    </app-layout>
</template>
