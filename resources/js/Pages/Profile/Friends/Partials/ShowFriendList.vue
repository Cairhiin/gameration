<script lang="ts" setup>
import { computed, type PropType } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { capitalize } from '@/Utils/index.ts';
import IconButton from '@/Components/Custom/IconButton.vue';
import type { User } from '@/Types';
import type { InertiaPageProps } from '@/Types/inertia';

const props = defineProps({
    selectedFriend: Object as PropType<User>,
    friends: Object as PropType<User[]>,
    pendingInvites: Object as PropType<User[]>,
    pendingFriends: Object as PropType<User[]>,
});

const page = usePage<InertiaPageProps>();

const emit = defineEmits<{
    select: [friend: User]
    send: [friend: User]
}>();

const highlightedFriend = computed<User>(() => props.selectedFriend || props.friends[0]);

/**
 * Computed property to format the list of friends. If the friend is the current user,
 * return the friend object as is. Otherwise, adjust the pivot data to set the correct user IDs.
 */
/* const formattedFriends = computed<User[]>(() =>
    props.friends.map((friend) => {
        // Check if the friend is the current user
        if (friend.pivot.user_id === page.props.auth.user.id) {
            return friend;
        }

        // Return a new friend object with adjusted pivot data
        return {
            ...friend,
            pivot: {
                ...friend.pivot,
                friend_id: friend.pivot.user_id,
                user_id: page.props.auth.user.id,
            },
        };
    })
); */

const handleInvite = (user: User): void => {
    router.put(route('profile.friends.update', { user: user }), { accepted: true });
};

const declineInvite = (user: User): void => {
    router.put(route('profile.friends.update', { user: user }), { accepted: false });
}

const handleRemoveFriend = (friend: User): void => {
    router.delete(route('profile.friends.delete', { user: friend.pivot.friend_id }));
}
</script>

<template>
    <div class="mb-8 xl:grid xl:grid-cols-3 xl:gap-8">
        <div class="mb-8">
            <h2 class="font-bold uppercase text-sm my-2 text-light/80">Friends</h2>
            <ul v-if="friends.length" class="text-sm uppercase text-lightVariant">
                <li v-for="friend in props.friends" :key="friend.id" @click="emit('select', friend)"
                    class="h-16 flex items-center justify-between w-full bg-dark-box/40 border-l-8 px-4 py-2 cursor-pointer mb-[2px] last:mb-0"
                    :class="{
                        'border-l-dark-highlight-variant': friend.pivot.friend_id === highlightedFriend.pivot.friend_id,
                        'border-l-dark-box/40': friend.pivot.friend_id !== highlightedFriend.pivot.friend_id
                    }">
                    <div>{{ capitalize(friend.username) }}
                    </div>
                    <div class="flex gap-2" v-if="friend.pivot.friend_id === highlightedFriend.pivot.friend_id">
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
            <div v-else
                class="flex items-center justify-start h-16 border-l-8 border-transparent px-4 bg-dark-box/40 text-sm uppercase text-lightVariant">
                No Friends</div>
        </div>

        <div class="mb-8">
            <h2 class="font-bold uppercase text-sm my-2 text-light/80">Pending Invites</h2>
            <ul class="text-sm uppercase text-lightVariant" v-if="pendingInvites.length">
                <li v-for="invitedUser in pendingInvites" :key="invitedUser.pivot.friend_id"
                    class="h-16 flex items-center justify-between w-full bg-dark-box/40 border-l-8 px-4 py-2 cursor-pointer mb-[2px] last:mb-0 border-l-dark-box/40">
                    <div>{{ capitalize(invitedUser.username) }}
                    </div>
                    <div class="flex gap-4 justify-end">
                        <span @click="handleInvite(invitedUser)" size="text-xs"><i
                                class="fa fa-solid fa-check"></i></span>
                        <span @click="declineInvite(invitedUser)" size="text-xs"><i
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
