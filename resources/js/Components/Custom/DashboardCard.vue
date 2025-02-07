<script setup>
import SubHeader from '@/Components/Custom/SubHeader.vue';
</script>

<template>
    <section class="flex flex-col justify-between border border-lightVariant/10 p-4 rounded-lg shadow-md min-h-96">
        <div>
            <sub-header level="h3" class="text-center mt-4 mb-8 mx-auto">
                <slot name="title" />
            </sub-header>
            <slot name="content" />
        </div>
        <div class="flex justify-end">
            <div class="w-32">
                <slot name="buttons" />
            </div>
        </div>
    </section>
</template>
