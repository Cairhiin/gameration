<script setup>
import { ref } from 'vue';
import { capitalize } from '@/Utils/index.ts';

const props = defineProps({
    friends: Array,
    pendingInvites: Array,
    pendingFriends: Array
});

const selectedFriend = ref({});

defineExpose({ selectedFriend });

const handleInvite = (user, invite) => {
    router.put(route('profile.friends.update', { user: user }), { accepted: invite })
};
</script>

<template>
    <section class="w-1/4 backdrop-blur-sm bg-dark/70 rounded-lg border border-darkVariant p-8 my-8">
        <div class="mb-4">
            <h2>Friends</h2>
            <ul v-if="friends.length" class="text-sm uppercase text-lightVariant">
                <li v-for="friend in friends" :key="friend.id" @click="selectedFriend = friend"
                    class="flex gap-4 justify-between items-center py-1 hover:cursor-pointer hover:text-light/70"
                    :class="selectedFriend?.friend_id === friend.friend_id ? 'font-bold text-light/70' : ''">
                    <div>{{ capitalize(friend.username) }}
                    </div>
                </li>
            </ul>
            <div v-else class="text-sm uppercase text-lightVariant">No Friends</div>
        </div>

        <div class="mb-4" v-if="pendingInvites.length">
            <h2>Pending Invites</h2>
            <ul class="text-sm uppercase text-lightVariant">
                <li v-for="invite in pendingInvites" :key="invite.friend_id"
                    class="flex gap-4 justify-between items-center py-1">
                    <div>{{ capitalize(invite.username) }}
                    </div>
                    <div class="flex gap-4 justify-end">
                        <span @click="handleInvite(invite.pivot.user_id, true)" size="text-xs"><i
                                class="fa fa-solid fa-check"></i></span>
                        <span @click="handleInvite(invite.pivot.user_id, false)" size="text-xs"><i
                                class="fa fa-solid fa-xmark"></i></span>
                    </div>
                </li>
            </ul>
        </div>

        <div class="mb-4" v-if="pendingFriends.length">
            <h2>Pending Friends</h2>
            <ul class="text-sm uppercase text-lightVariant">
                <li v-for="friend in pendingFriends" :key="friend.id" class="flex gap-4 justify-between items-center">
                    {{ capitalize(friend.username) }}
                </li>
            </ul>
        </div>
    </section>
</template>
