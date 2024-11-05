<script setup>
import SubHeader from '@/Components/Custom/SubHeader.vue';
</script>
<template>
    <section
        class="flex flex-col justify-between bg-gradient-to-br from-dark-box/60 to-dark-box/20 p-4 rounded-lg backdrop-blur-sm min-h-96">
        <div>
            <sub-header level="h3" class="text-center mb-4">
                <slot name="title" />
            </sub-header>
            <slot name="content" />
        </div>
        <slot name="buttons" />
    </section>
</template>
