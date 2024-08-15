<script setup>
import GameForm from '@/Components/Forms/GameForm.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    game: Object
});
</script>

<template>
    <app-layout :title="game.name">
        <game-form :game="game" />
    </app-layout>
</template>
