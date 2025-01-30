<script lang="ts" setup>
</script>

<template>
    <li class="text-sm text-lightVariant">
        <slot name="label" />
        <span class="ml-2 font-bold text-light text-lg">
            <slot name="value" />
        </span>
    </li>
</template>
