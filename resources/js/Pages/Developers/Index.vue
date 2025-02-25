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
        <div class="article-header relative overflow-hidden">
            <div class="py-4">
                <h3 class="relative font-bold uppercase text-5xl text-light">Developers</h3>
            </div>
        </div>

        <div v-if="developers.length">
            <data-table :data="developers" @show="showDeveloper" />
        </div>

        <div v-else>No developers found.</div>

        <!-- Admin Create Section -->
        <admin-create-section />
    </app-layout>
</template>
