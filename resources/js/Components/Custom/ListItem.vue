<script lang="ts" setup>
</script>

<template>
    <li class="text-sm text-lightVariant grid grid-cols-4 items-center gap-4">
        <div class="uppercase">
            <slot name="label" />
        </div>
        <span class=" text-light text-lg col-span-3">
            <slot name="value" />
        </span>
    </li>
</template>
