<script setup>
import { capitalize } from '@/Utils/index.ts';

defineProps({
    publishers: Array
});
</script>

<template>
    <div>
        <h2>Publishers</h2>
        <ul>
            <li v-for="publisher in publishers" :key="publisher.id">
                {{ capitalize(publisher.name) }}
            </li>
        </ul>
    </div>
</template>
