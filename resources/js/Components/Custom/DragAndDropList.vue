<script lang="ts" setup>
import type { Game } from '@/Types';
import { ref, type PropType } from 'vue';
import draggable from 'vuedraggable';

const { list } = defineProps({
    list: Array as PropType<Game[]>
});

const userList = ref<Game[]>(list);
const enabled = ref(true);
const dragging = ref(false);

const add = function (game: Game) {
    userList.value.push(game);
};
const replace = function (game: Game) {
    userList.value = [game];
};
const checkMove = function (e) {
    console.log("Future index: " + e.draggedContext.futureIndex);
}
</script>

<template>
    <div>
        <draggable :list="userList" :disabled="!enabled" item-key="name" class="list-group" ghost-class="ghost"
            :move="checkMove" @start="dragging = true" @end="dragging = false">
            <template #item="{ element }">
                <div class="list-group-item" :class="{ 'not-draggable': !enabled }">
                    {{ element.name }}
                </div>
            </template>
        </draggable>
    </div>
</template>
