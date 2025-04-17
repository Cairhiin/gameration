<script setup lang="ts">
import { computed, type PropType } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import type { Person } from '@/Types';

const { persons } = defineProps({
    persons: Array as PropType<Person[]>
});

const authors = computed(() => {
    return persons.filter((person: Person) => person.type === 'author');
});

const narrators = computed(() => {
    return persons.filter((person: Person) => person.type === 'narrator');
});
</script>

<template>
    <app-layout title="Create Person">
        <h3>Authors</h3>
        <ul>
            <li v-for="person in authors" :key="person.id">
                {{ person.name }}
            </li>
        </ul>

        <h3>Narrators</h3>
        <ul>
            <li v-for="person in narrators" :key="person.id">
                {{ person.name }}
            </li>
        </ul>
    </app-layout>
</template>
