<script lang="tsx" setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import type { Review, User } from '@/Types';
import type { PropType } from 'vue';

const { data, newUsers, unapprovedReviews } = defineProps({
    data: Object as PropType<{ totalUsers: number; totalAuthors: number; totalBooks: number }>,
    newUsers: Array as PropType<User[]>,
    unapprovedReviews: Array as PropType<Review[]>,
});
console.log(newUsers, unapprovedReviews);
const importBooks = () => {
    router.post(route('admin.import.books'));
};

const importAuthors = () => {
    router.post(route('admin.import.authors'));
};
</script>

<template>
    <app-layout title="Admin">
        <div class="max-w-4xl mx-auto px-4 py-8">
            <h2 class="text-4xl font-bold mb-4">Admin Dashboard</h2>
            <section class="mb-8 grid grid-cols-3 gap-8">
                <div class="bg-darkVariant/25 rounded-xl shadow-md p-4">
                    <div>Books</div>
                    <div>{{ data.totalBooks }}
                    </div>
                </div>

                <div class="bg-darkVariant/25 rounded-xl shadow-md p-4">
                    <div>Authors</div>
                    <div>{{ data.totalAuthors }}</div>
                </div>

                <div class="bg-darkVariant/25 rounded-xl shadow-md p-4">
                    <div>Users</div>
                    <div>{{ data.totalUsers }}</div>
                </div>
            </section>

            <section>
                <h3 class="text-xl font-bold mb-2 ml-2">Newest Users</h3>
                <div class="bg-darkVariant/25 rounded shadow-md mb-4 md:mb-8 p-4">
                    <ul v-if="newUsers.length">
                        <li v-for="user in newUsers" :key="user.id" class="mb-2">
                            {{ user.username }}
                        </li>
                    </ul>
                    <p v-else class="text-gray-500">No new users found.</p>
                </div>
            </section>

            <section>
                <h3 class="text-xl font-bold mb-2 ml-2">Unapproved Reviews</h3>
                <div class="bg-darkVariant/25 rounded shadow-md mb-4 md:mb-8 p-4">
                    <ul v-if="unapprovedReviews.length">
                        <li v-for="review in unapprovedReviews" :key="review.id" class="mb-2">
                            {{ review.content }}
                        </li>
                    </ul>
                    <p v-else class="text-gray-500">No unapproved reviews found.</p>
                </div>
            </section>
        </div>
    </app-layout>
</template>
