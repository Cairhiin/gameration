<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const { user } = defineProps({
    user: Object
});
console.log(user)
</script>

<template>
    <AppLayout title="Dashboard">
        <div>
            <div>
                <h4>Latest Ratings</h4>
                <ul>
                    <li v-for="rating in user.ratings" :key="rating.id"></li>
                </ul>
            </div>
        </div>
    </AppLayout>
</template>
