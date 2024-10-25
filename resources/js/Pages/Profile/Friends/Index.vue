<script setup>
import { ref, onMounted } from 'vue';
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

onMounted(() => {
    if (props.friends.length > 0) {
        selectedFriend.value = props.friends[0];
    }
});

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
    router.delete(route('profile.friends.delete', { user: user }), {
        preserveState: false,
    });
};

const selectFriend = (friend) => {
    selectedFriend.value = friend;
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
                    <primary-button @click="openMessageModal(selectedFriend)">Send
                        Message</primary-button>
                    <danger-button @click="deleteFriend(selectedFriend)" variant="outline">Delete
                        Friend</danger-button>
                </template>
            </show-friend-messages>


            <!-- Friends -->
            <show-friend-list :friends="friends" :pendingFriends="pendingFriends" :pendingInvites="pendingInvites"
                :selectedFriend="selectedFriend" @select="selectFriend" />
        </div>
    </app-layout>

    <message-form :isOpen="isMessageModalOpen" :friend="selectedFriend" @close="closeMessageModal" />
</template>
