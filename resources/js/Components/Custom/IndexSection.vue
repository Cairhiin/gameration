<script lang="ts" setup>
</script>

<template>
    <section class="w-full bg-dark-box/30 px-4 py-12 my-4 rounded-lg">
        <div class="container mx-auto max-w-sm md:max-w-lg lg:max-w-2xl xl:max-w-4xl ">
            <h2 class="uppercase font-bold text-default mb-2 text-lg">
                <slot name="title"></slot>
            </h2>
            <slot name="content"></slot>
        </div>
    </section>
</template>
