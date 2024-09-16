<script setup>
import SubHeader from '@/Components/Custom/SubHeader.vue';
</script>
<template>
    <section
        class="flex flex-col justify-between bg-highlight/25 p-4 rounded-lg shadow border border-darkVariant backdrop-blur-sm">
        <div>
            <sub-header level="h3" class="text-center mb-4">
                <slot name="title" />
            </sub-header>
            <slot name="content" />
        </div>
        <slot name="buttons" />
    </section>
</template>
