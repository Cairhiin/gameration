<script setup>
import SubHeader from '@/Components/Custom/SubHeader.vue';
</script>
<template>
    <section class="bg-highlight/25 p-4 rounded-lg shadow border border-darkVariant backdrop-blur-sm">
        <sub-header level="h3" class="text-center mb-4">
            <slot name="title" />
        </sub-header>
        <slot name="content" />
    </section>
</template>
