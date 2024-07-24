<script setup>

defineProps({
    game: Object
});
</script>

<template>
    <div>
        {{ game.name }}
    </div>
</template>
