<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from '@/Components/Custom/DataTable.vue';
import AdminCreateSection from '@/Components/Custom/AdminCreateSection.vue';

const { developers } = defineProps({
    developers: Array
});

const showDeveloper = (developer) => {
    if (!developer.id) {
        const developerClicked = developers.filter((d) => d.name === developer.name);
        developer.id = developerClicked.length > 0 ? developerClicked[0].id : null;
    }
    router.get(route('developers.show', developer.id));
}

const formattedDevelopersForTable = computed(() => {
    return developers.map((developer) => {
        return {
            name: developer.name,
            avg_rating: developer.avg_rating?.toFixed(1),
            games_count: developer.games_count,
        };
    });
});
</script>

<template>
    <app-layout title="Developers">
        <h2>Developers</h2>
        <data-table :data="formattedDevelopersForTable" :headers="['Genre', 'Avg Rating', 'Games Count']"
            @show="showDeveloper" />

        <!-- Admin Create Section -->
        <admin-create-section />
    </app-layout>
</template>
