<script setup>
import { ref, computed } from 'vue';
import { usePage, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SearchInput from '@/Components/Forms/SearchInput.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import DangerButton from '@/Components/Custom/DangerButton.vue';
import MessageForm from '@/Components/Forms/MessageForm.vue';
import ShowFriendMessages from './Partials/ShowFriendMessages.vue';
import ShowFriendList from './Partials/ShowFriendList.vue';

const page = usePage();

const props = defineProps({
    friends: Array,
    pendingFriends: Array,
    pendingInvites: Array,
});

const isMessageModalOpen = ref(false);
const messages = ref(null);
const selectedFriend = ref(null);

const form = useForm({
    username: null
});

const submit = () => {
    form.post(route('profile.friends.store', page.props.auth.user), {
        errorBag: 'addFriend',
        preserveScroll: true,
        preserveState: "errors.addFriend",
        onSuccess: () => form.reset(),
        onError: (err) => console.log(err)
    });
};

const getFriend = (result) => {
    form.username = result.username
};

const deleteFriend = (user) => {
    router.delete(route('profile.friends.delete', { user: user }));
};

const retrieveMessages = (friend) => {
    selectedFriend.value = friend;
    axios.get(route('profile.friends.messages', { user: friend.friend_id })).then(
        (res) => messages.value = res.data
    );

}
const openMessageModal = () => {
    isMessageModalOpen.value = true;
};

const closeMessageModal = (friend) => {
    isMessageModalOpen.value = false;
};
</script>

<template>
    <app-layout title="Friends">
        <div>
            <form class="flex gap-4">
                <search-input searchType="users" :value="form.username" @update:model-value="getFriend"
                    class="flex-none w-64" />
                <div>
                    <primary-button @click="submit">Add</primary-button>
                </div>
            </form>
        </div>

        <div class="flex gap-4">

            <!-- Messages -->
            <show-friend-messages :friend="selectedFriend">

                <!-- Actions -->
                <template #actions>
                    <primary-button @click="openMessageModal(friend)">Send
                        Message</primary-button>
                    <danger-button @click="deleteFriend(friend)" variant="outline">Delete
                        Friend</danger-button>
                </template>
            </show-friend-messages>


            <!-- Friends -->
            <show-friend-list :friends="friends" :pendingFriends="pendingFriends" :pendingInvites="pendingInvites"
                :selectedFriend="selectedFriend" @select="retrieveMessages" />
        </div>
    </app-layout>

    <message-form :isOpen="isMessageModalOpen" :friend="selectedFriend" @close="closeMessageModal" />
</template>
