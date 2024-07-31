<script setup>
import GameForm from '@/Components/Forms/GameForm.vue';

defineProps({
    game: Object
});
</script>

<template>
    <game-form :game="game" />
</template>
