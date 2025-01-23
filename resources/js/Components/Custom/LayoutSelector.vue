<script setup>
const emit = defineEmits(['layout']);
</script>

<template>
    <ul id="layout__selector" class="flex items-center gap-4 text-dark-highlight-variant text-xl font-bold">
        <li><i class="fa-solid fa-table-cells-large cursor-pointer" @click="emit('layout', 'grid')"></i></li>
        <li><i class="fa-solid fa-list cursor-pointer" @click="emit('layout', 'list')"></i></li>
    </ul>
</template>
