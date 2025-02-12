<script lang="ts" setup>
</script>

<template>
    <li class="text-sm text-lightVariant grid grid-cols-5 items-center gap-2">
        <div class="uppercase col-span-2">
            <slot name="label" />
        </div>
        <span class=" text-light text-lg col-span-3 truncate">
            <slot name="value" />
        </span>
    </li>
</template>
