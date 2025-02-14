<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    numberOfGames: Number,
    numberOfRatings: Number,
    numberOfReviews: Number,
    numberOfDevelopers: Number,
    numberOfPublishers: Number,
});
</script>

<template>
    <AppLayout title="Home">
        <section class="px-6 py-12">
            <h2 class="text-5xl font-bold uppercase text-light my-8 text-center">Welcome to Gameration!
            </h2>
            <div class="flex justify-between gap-4 p-4 text-sm my-8 bg-darkVariant/30 shadow-md rounded-lg">
                <div class="text-center">
                    <div class="text-2xl font-semibold">{{ numberOfGames ?? 0 }}</div>
                    <div class="text-lightVariant">Games</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-semibold">{{ numberOfRatings ?? 0 }}</div>
                    <div class="text-lightVariant">Ratings</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-semibold">{{ numberOfReviews ?? 0 }}</div>
                    <div class="text-lightVariant">Reviews</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-semibold">{{ numberOfDevelopers ?? 0 }}</div>
                    <div class="text-lightVariant">Developers</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-semibold">{{ numberOfPublishers ?? 0 }}</div>
                    <div class="text-lightVariant">Publishers</div>
                </div>
            </div>
            <p class="text-lg text-light leading-relaxed max-w-3xl mx-auto text-center">
                Gameration is your ultimate destination for discovering, rating, and reviewing games. Whether you're a
                casual gamer or a hardcore enthusiast, our platform offers a comprehensive database of games across all
                genres and platforms.
            </p>
            <p class="text-lg text-light leading-relaxed max-w-3xl mx-auto text-center mt-8">
                Join our vibrant community of gamers to share your experiences, read reviews from other players, and
                find
                your next favorite game. Our user-friendly interface makes it easy to browse through the latest
                releases, top-rated games, and hidden gems.
            </p>
            <p class="text-lg text-light leading-relaxed max-w-3xl mx-auto text-center mt-8">
                Start exploring today and become a part of the Gameration community. Together, let's celebrate the joy
                of
                gaming!
            </p>
        </section>
    </AppLayout>
</template>

<style></style>
