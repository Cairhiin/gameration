<script lang="ts" setup>
import GameForm from '@/Components/Forms/GameForm.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import type { Game } from '@/Types';
import type { PropType } from 'vue';

defineProps(
    { game: Object as PropType<Game> }
);
</script>

<template>
    <app-layout :title="game.name">
        <game-form :game="game" />
    </app-layout>
</template>
