<script setup lang="ts">

</script>

<template>
    <section class="bg-dark-box/60 rounded-xl shadow-md mb-4 md:mb-8 p-8">
        <div class="font-bold uppercase text-sm overflow-hidden relative flex h-10 mb-2">
            <h3 class="text-2xl uppercase text-lightt font-bold">
                <slot name="header" />
            </h3>
        </div>
        <div class="text-light/70">
            <slot name="content" />
        </div>
    </section>
</template>
