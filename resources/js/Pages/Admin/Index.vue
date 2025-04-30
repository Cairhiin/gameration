<script lang="tsx" setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const importBooks = () => {
    console.log('Importing books...');
    router.post(route('admin.import.books'));
};

const importAuthors = () => {
    router.post(route('admin.import.authors'));
};
</script>

<template>
    <app-layout title="Admin">
        <div class="flex flex-col items-center justify-center min-h-screen py-6 sm:py-12 lg:py-24">
            <h1 class="text-4xl font-bold mb-4">Admin Dashboard</h1>
            <p class="text-lg text-gray-600 mb-8">Welcome to the admin dashboard. Here you can manage your content.</p>
            <nav class="flex gap-4">
                <div @click="importBooks" class="text-blue-500 hover:text-blue-700 hover:cursor-pointer">Import Books
                </div>
                <div @click="importAuthors" class="text-blue-500 hover:text-blue-700 hover:cursor-pointer">Import
                    Authors</div>
            </nav>
        </div>
    </app-layout>
</template>
