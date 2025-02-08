<script lang="ts" setup>

</script>

<template>
    <div class="h-full rounded-xl drop-shadow-md z-10">

        <div
            class=" flex flex-col justify-between gap-4 animated-background bg-gradient-to-r from-card-gradient-color-start to-dark-box/40 rounded-xl h-full">

            <!-- image -->
            <div
                class="relative w-full h-40 overflow-hidden rounded-t-xl [clip-path:polygon(0%_0%,100%_0%,100%_90%,0%_100%)]">

                <slot name="image"></slot>
            </div>

            <!-- content -->
            <div class="px-2 py-1 text-sm h-[9.5rem] overflow-hidden">
                <div id="card__header" class="uppercase text-lightVariant mb-2">
                    <slot name="title"></slot>
                </div>
                <div>
                    <slot name="content"></slot>
                </div>
            </div>

            <!-- release date -->
            <div class="text-right text-sm text-lightVariant py-2 px-4 font-bold">
                <slot name="release-date"></slot>
            </div>
        </div>

    </div>
</template>

<style scoped>
.animated-background {
    background-size: 400%;

    -webkit-animation: animation 3s ease infinite;
    -moz-animation: animation 3s ease infinite;
    animation: animation 3s ease infinite;
}

@keyframes animation {

    0%,
    100% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }
}
</style>
