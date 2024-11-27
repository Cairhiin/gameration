<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminCreateSection from '@/Components/Custom/AdminCreateSection.vue';
import DataTable from '@/Components/Custom/DataTable.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const { genres } = defineProps({
    genres: Array
});

const formattedGenresForTable = computed(() => {
    return genres.map((genre) => {
        return {
            name: genre.name,
            avg_rating: genre.avg_rating.toFixed(1),
            games_count: genre.games_count,
        };
    });
});

const showGenre = (genre) => {
    // The DataTable component passes the entire table row object to the `show`
    // event. This object is a formatted version of the genre object from the
    // `genres` prop. The `id` property is removed from the `genres` array before
    // passing it to the DataTable component to prevent the `show` event from
    // being triggered when the user clicks on the "Show" button.
    //
    // However, when the user clicks on a row, the `show` event is triggered with
    // the formatted genre object. This object does not have an `id` property, so
    // we need to find the genre object from the `genres` array that matches the
    // `name` property of the formatted genre object and set the `id` property of
    // the formatted genre object to the `id` of the matching genre object.
    //
    // If the `genres` array does not contain a genre object with the same `name`
    // as the formatted genre object, the `id` property is set to null.
    if (!genre.id) {
        const genreClicked = genres.filter((g) => g.name === genre.name);
        genre.id = genreClicked.length > 0 ? genreClicked[0].id : null;
    }
    router.get(route('genres.show', genre.id));
};
</script>

<template>
    <app-layout title="Genres">
        <h2>Genres</h2>
        <data-table :data="formattedGenresForTable" :headers="['Genre', 'Avg Rating', 'Games Count']"
            @show="showGenre" />

        <!-- Admin Create Section -->
        <admin-create-section />
    </app-layout>
</template>
