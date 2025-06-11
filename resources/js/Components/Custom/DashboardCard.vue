<script setup>
import SubHeader from '@/Components/Custom/SubHeader.vue';
</script>

<template>
    <section class="bg-gray-800/90 flex flex-col justify-between p-4 rounded-lg shadow-md min-h-60">
        <div>
            <sub-header level="h3" class="text-center m-4 mx-auto">
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
