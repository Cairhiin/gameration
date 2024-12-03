<script setup>
import SubHeader from '@/Components/Custom/SubHeader.vue';
import GameList from '@/Components/Custom/GameList.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    publisher: Object
})
</script>

<template>
    <app-layout :title="publisher.name">
        <section
            class="flex gap-4 px-4 py-2 mb-4 justify-between bg-gradient-to-r from-transparent to-dark-highlight-variant/5">
            <sub-header level="h3" class="truncate">{{ publisher.name }}</sub-header>
            <div class="flex gap-4">
                <div class="text-sm text-lightVariant uppercase">
                    <span class="hidden lg:inline-block">City:</span> <span
                        class="text-lg font-bold text-light capitalize">{{
                            publisher.city
                        }}</span>
                </div>
                <div class="text-sm text-lightVariant uppercase">
                    <span class="hidden lg:inline-block">Country:</span> <span
                        class="text-lg font-bold text-light capitalize">{{
                            publisher.country }}</span>
                </div>
                <div class="text-sm text-lightVariant uppercase">
                    <span class="hidden lg:inline-block">Since:</span> <span class="text-lg font-bold text-light">{{
                        publisher.year
                    }}</span>
                </div>
                <div class="text-sm text-lightVariant uppercase hidden lg:inline-block">
                    <span>Games:</span> <span class="text-lg font-bold text-light capitalize">{{
                        publisher.games_count }}</span>
                </div>
            </div>
        </section>

        <!-- Games -->
        <section class="backdrop-blur-sm">
            <game-list :games="publisher.games" />
        </section>

    </app-layout>
</template>
