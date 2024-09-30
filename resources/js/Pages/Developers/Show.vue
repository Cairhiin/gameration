<script setup>
import SubHeader from '@/Components/Custom/SubHeader.vue';
import GameList from '@/Components/Custom/GameList.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    developer: Object
})
</script>

<template>
    <app-layout :title="developer.name">
        <section>
            <sub-header level="h3">{{ developer.name }}</sub-header>
            <div class="flex gap-4 mb-4 p-4 rounded bg-dark/75 border border-darkVariant">
                <div class="text-sm text-lightVariant uppercase">Since: <span class="text-lg font-bold text-light">{{
        developer.year
    }}</span>
                </div>
                <div class="text-sm text-lightVariant uppercase">City: <span
                        class="text-lg font-bold text-light capitalize">{{
            developer.city
        }}</span></div>
                <div class="text-sm text-lightVariant uppercase">Country: <span
                        class="text-lg font-bold text-light capitalize">{{
            developer.country }}</span></div>
                <div class="text-sm text-lightVariant uppercase">Games: <span
                        class="text-lg font-bold text-light capitalize">{{
                        developer.games_count }}</span></div>
            </div>
        </section>

        <!-- Games -->
        <section class="backdrop-blur-sm">
            <game-list :games="developer.games" />
        </section>

    </app-layout>
</template>
