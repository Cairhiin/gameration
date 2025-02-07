<script lang="ts" setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import type { InertiaPageProps } from '@/Types/inertia';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage<InertiaPageProps>();

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <AppLayout title="Home">

    </AppLayout>
</template>

<style></style>
