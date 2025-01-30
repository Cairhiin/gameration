<script lang="ts" setup>
import { computed, type PropType } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from '@/Components/Custom/DataTable.vue';
import AdminCreateSection from '@/Components/Custom/AdminCreateSection.vue';
import type { Developer } from '@/Types';

const { developers } = defineProps({
    developers: Object as PropType<Developer[]>
});

const showDeveloper = (developer_id: string): void => {
    router.get(route('developers.show', developer_id));
}
</script>

<template>
    <app-layout title="Developers">
        <h2>Developers</h2>
        <data-table :data="developers" @show="showDeveloper" />

        <!-- Admin Create Section -->
        <admin-create-section />
    </app-layout>
</template>
