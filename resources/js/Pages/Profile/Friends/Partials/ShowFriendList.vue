<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { capitalize } from '@/Utils/index.ts';
import IconButton from '@/Components/Custom/IconButton.vue';

const props = defineProps({
    selectedFriend: Object,
    friends: Array,
    pendingInvites: Array,
    pendingFriends: Array
});

const page = usePage();

const emit = defineEmits(['select', 'send']);

const highlightedFriend = computed(() => props.selectedFriend || props.friends[0]);

const handleInvite = (user) => {
    router.put(route('profile.friends.update', { user: user }), { accepted: true });
};

const declineInvite = (user) => {
    router.put(route('profile.friends.update', { user: user }), { accepted: false });
}

const handleRemoveFriend = (friend) => {
    const friendId = friend.friend_id === page.props.auth.user.id ? friend.pivot.user_id : friend.friend_id;

    router.delete(route('profile.friends.delete', { user: friendId }));
}
</script>

<template>
    <div class="mb-8 xl:grid xl:grid-cols-3 xl:gap-8">
        <div class="mb-8">
            <h2 class="font-bold uppercase text-sm my-2 text-light/80">Friends</h2>
            <ul v-if="friends.length" class="text-sm uppercase text-lightVariant">
                <li v-for="friend in friends" :key="friend.id" @click="emit('select', friend)"
                    class="h-16 flex items-center justify-between w-full bg-dark-box/40 border-l-8 px-4 py-2 cursor-pointer mb-[2px] last:mb-0"
                    :class="{
                'border-l-dark-highlight-variant': friend.friend_id === highlightedFriend.friend_id,
                'border-l-dark-box/40': friend.friend_id !== highlightedFriend.friend_id
            }">
                    <div>{{ capitalize(friend.username) }}
                    </div>
                    <div class="flex gap-2" v-if="friend.friend_id === highlightedFriend.friend_id">
                        <icon-button size="text-lg" variant="invert" icon="fa-user"
                            @click="router.visit(route('profile.show', { user: friend.username }))"
                            tooltip-text="View Profile" />
                        <icon-button size="text-lg" variant="invert" icon="fa-arrow-trend-up"
                            @click="router.visit(route('profile.show', { user: friend.username }))"
                            tooltip-text="View Ratings" />
                        <icon-button size="text-lg" variant="invert" icon="fa-envelope" @click="emit('send', friend)"
                            tooltip-text="Send Message" />
                        <icon-button size="text-lg" variant="invert" warning icon="fa-trash"
                            tooltip-text="Remove Friend" @click="handleRemoveFriend(friend)" />
                    </div>
                </li>
            </ul>
            <div v-else class="text-sm uppercase text-lightVariant">No Friends</div>
        </div>

        <div class="mb-8">
            <h2 class="font-bold uppercase text-sm my-2 text-light/80">Pending Invites</h2>
            <ul class="text-sm uppercase text-lightVariant" v-if="pendingInvites.length">
                <li v-for="invite in pendingInvites" :key="invite.friend_id"
                    class="h-16 flex items-center justify-between w-full bg-dark-box/40 border-l-8 px-4 py-2 cursor-pointer mb-[2px] last:mb-0 border-l-dark-box/40">
                    <div>{{ capitalize(invite.username) }}
                    </div>
                    <div class="flex gap-4 justify-end">
                        <span @click="handleInvite(invite.pivot.user_id)" size="text-xs"><i
                                class="fa fa-solid fa-check"></i></span>
                        <span @click="declineInvite(invite.pivot.user_id)" size="text-xs"><i
                                class="fa fa-solid fa-xmark"></i></span>
                    </div>
                </li>
            </ul>
            <div v-else
                class="flex items-center justify-start h-16 border-l-8 border-transparent px-4 bg-dark-box/40 text-sm uppercase text-lightVariant">
                No
                Pending Invites</div>
        </div>

        <div class="mb-8" v-if="pendingFriends.length">
            <h2 class="font-bold uppercase text-sm my-2 text-light/80">Pending Friends</h2>
            <ul class="text-sm uppercase text-lightVariant" v-if="pendingFriends.length">
                <li v-for="friend in pendingFriends" :key="friend.id"
                    class="pointer-events-none h-16 flex items-center justify-between w-full bg-dark-box/40 border-l-8 px-4 py-2 cursor-pointer mb-[2px] last:mb-0 border-l-dark-box/40">
                    {{ capitalize(friend.username) }}
                </li>
            </ul>
            <div v-else
                class="flex items-center justify-start h-16 border-l-8 border-transparent px-4 bg-dark-box/40 text-sm uppercase text-lightVariant">
                No
                Pending Friends</div>
        </div>
    </div>
</template>
