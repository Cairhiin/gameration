<script lang="ts" setup">
import AppLayout from '@/Layouts/AppLayout.vue';
import type { Data, User } from '@/Types';
import type { PropType } from 'vue';

const { games } = defineProps({
    games: Object as PropType<Data<User>>,
});
</script>

<template>
    <app-layout :title="`${games?.data[0]?.users[0]?.user_name} Rated Games`">
        <h2>Games by {{ games?.data[0]?.users[0]?.user_name }}</h2>
        <ul>
            <li v-for="game in games.data" :key="game.id">{{ game.name }} ({{ game.users[0].pivot.rating }})
            </li>
        </ul>
    </app-layout>
</template>
