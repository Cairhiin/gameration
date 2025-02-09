<script lang="ts" setup>
import type { Genre, Publisher, Developer } from '@/Types';
import { computed, type PropType } from 'vue';

const props = defineProps({
    data: Object as PropType<Genre[] | Publisher[] | Developer[]>,
});

const emit = defineEmits<{
    show: [item_id: string]
}>();

const formattedDataForTable = computed<{ id: string, name: string, avg_rating: number, games_count: number }[]>(() => {
    return props.data.map((item: Genre | Publisher | Developer): { id: string, name: string, avg_rating: number, games_count: number } => {
        return {
            id: item.id,
            name: item.name,
            avg_rating: item.avg_rating,
            games_count: item.games_count,
        };
    });
});

const headers: string[] = ['Genre', 'Avg Rating', 'Games Count'];
</script>

<template>
    <table class="table-fixed w-full ">
        <thead class="bg-dark-box/40 text-lightVariant text-base uppercase font-bold">
            <td class="px-2 py-4 w-1/2 [&:not(:first-child)]:text-center" v-for="header in headers">
                {{ header }}
            </td>
        </thead>
        <tr v-for="item in formattedDataForTable" :key="item.name" @click="emit('show', item.id)"
            class="w-full p-4 bg-transparent  hover:bg-dark-highlight-variant/15 cursor-pointer border-t border-dark-box/40">
            <template v-for="(i, index) in item">
                <td class="p-2 [&:not(:first-child)]:text-center" v-if="index !== 'id'">
                    {{ index === "avg_rating" && typeof i === "number" ? i.toFixed(1) : i }}
                </td>
            </template>
        </tr>
    </table>
</template>
