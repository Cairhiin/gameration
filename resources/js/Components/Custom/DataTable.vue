<script setup>
import { computed } from 'vue';

const props = defineProps({
    data: Array,
    headers: {
        type: Array,
        default: [],
    },
});

const emit = defineEmits(['show']);

const headers = computed(() => props.headers.length ? props.headers : Object.keys(props.data[0]));
</script>

<template>
    <table class="table-fixed w-full border border-dark-highlight-variant/25">
        <thead class="bg-dark-highlight-variant text-dark text-sm uppercase font-bold">
            <td class="px-2 py-4 w-1/2 [&:not(:first-child)]:text-center" v-for="header in headers">
                {{ header }}
            </td>
        </thead>
        <tr v-for="item in data" :key="item.id" @click="emit('show', item)"
            class="w-full p-4 odd:bg-transparent even:bg-dark-highlight-variant/5 hover:bg-dark-highlight-variant/15 cursor-pointer border-t border-dark-highlight-variant/25">
            <td class="p-2 [&:not(:first-child)]:text-center" v-for="i in item">
                {{ i }}
            </td>
        </tr>
    </table>
</template>
