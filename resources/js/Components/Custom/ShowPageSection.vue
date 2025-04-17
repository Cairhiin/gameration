<script setup lang="ts">

</script>

<template>
    <section class="bg-darkVariant/25 rounded-b-xl border border-darkVariant/15 shadow-md mb-4 md:mb-8">
        <div class="font-bold uppercase text-sm overflow-hidden relative flex h-10">
            <h3 class="relative font-bold bg-dark-highlight-variant text-dark px-8 flex items-center h-10 w-80 mb-1 border-b-[3px] border-dark border-r-[3px]
                    after:absolute after:block after:top-0 after:-right-4 after:w-1 after:-skew-x-[3.5rad] after:h-full after:bg-dark
                    before:absolute before:block before:top-0 before:-right-3 before:w-8 before:-skew-x-[3.5rad] before:h-full before:bg-dark-highlight-variant
                    ">
                <slot name="header" />
            </h3>
        </div>
        <div class="p-8">
            <slot name="content" />
        </div>
    </section>
</template>
