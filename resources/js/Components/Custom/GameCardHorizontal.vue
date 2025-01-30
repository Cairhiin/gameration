<script lang="ts" setup>
import type { Game } from '@/Types';
import { Link } from '@inertiajs/vue3';
import type { PropType } from 'vue';

defineProps({
    game: Object as PropType<Game>
});
</script>

<template>
    <div class="rounded-xl hover:scale-105 transition duration-150 ease-in-out drop-shadow-md z-10
        after:content-[''] after:absolute after:inset-[-2px] after:rounded-xl after:z-[-1]
        hover:after:bg-gradient-to-br hover:after:from-card-gradient-color-start hover:after:to-card-gradient-color-end
        hover:before:from-card-gradient-color-start hover:before:to-card-gradient-color-end hover:before:bg-gradient-to-br hover:before:content-[''] before:absolute
        before:inset-[-2px] hover:before:rounded-xl hover:before:z-[-2] hover:before:blur-md">
        <Link :href="route('games.show', game.id)">
        <div class="flex flex-col gap-8 bg-dark-box h-full rounded-xl">
            <div
                class="relative w-full h-40 overflow-hidden rounded-t-xl [clip-path:polygon(0%_0%,100%_0%,100%_90%,0%_100%)]">
                <div
                    class="absolute flex right-3 top-3 h-6 w-12 rounded bg-darkVariant/95 text-sm text-dark-highlight-variant items-center justify-center font-bold">
                    <slot name="rating"></slot>
                </div>
                <slot name="image"></slot>
            </div>

            <div class="p-2 text-sm">
                <div id="card__header" class="uppercase text-lightVariant mb-2">
                    <slot name="title"></slot>
                </div>
                <div id="card__header">
                    <slot name="content"></slot>
                </div>
            </div>
        </div>
        </Link>
    </div>
</template>
