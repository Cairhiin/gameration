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
        <section>
            <sub-header level="h3">{{ publisher.name }}</sub-header>
            <div class="flex gap-4 mb-4 p-4 rounded bg-dark/75 border border-darkVariant">
                <div class="text-sm text-lightVariant uppercase">Since: <span class="text-lg font-bold text-light">{{
        publisher.year
    }}</span>
                </div>
                <div class="text-sm text-lightVariant uppercase">City: <span
                        class="text-lg font-bold text-light capitalize">{{
            publisher.city
        }}</span></div>
                <div class="text-sm text-lightVariant uppercase">Country: <span
                        class="text-lg font-bold text-light capitalize">{{
            publisher.country }}</span></div>
                <div class="text-sm text-lightVariant uppercase">Games: <span
                        class="text-lg font-bold text-light capitalize">{{
                        publisher.games_count }}</span></div>
            </div>
        </section>

        <!-- Games -->
        <section class="backdrop-blur-sm">
            <game-list :games="publisher.games" />
        </section>

    </app-layout>
</template>
