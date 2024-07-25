<script setup>
import { capitalize } from '@/Utils/index.ts';

defineProps({
    developers: Array
});
</script>

<template>
    <div>
        <h2>Developers</h2>
        <ul>
            <li v-for="developer in developers" :key="developer.id">
                {{ capitalize(developer.name) }}
            </li>
        </ul>
    </div>
</template>
