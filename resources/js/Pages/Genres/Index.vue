<script lang="ts" setup>
import { computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AdminCreateSection from '@/Components/Custom/AdminCreateSection.vue';
import DataTable from '@/Components/Custom/DataTable.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import SearchInput from '@/Components/Forms/SearchInput.vue';
import type { PropType } from 'vue';
import type { Genre } from '@/Types';

const { genres } = defineProps({
    genres: Array as PropType<Genre[]>,
});

const showGenre = (genre_id: string): void => {
    router.get(route('genres.show', genre_id));
};

const form = useForm<{ genre: Genre }>({
    genre: null,
});

const getGenre = (result: Genre): void => {
    form.genre = result
};
</script>

<template>
    <app-layout title="Genres">
        <h2>Genres</h2>
        <data-table :data="genres" @show="showGenre" />

        <!-- Admin Create Section -->
        <admin-create-section />

        <search-input search-type="genres" :value="form.genre" @update:model-value="getGenre"></search-input>
    </app-layout>
</template>
