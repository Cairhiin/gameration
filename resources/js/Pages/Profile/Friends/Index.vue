<script setup>
import { ref, watch } from 'vue';
import { usePage, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SearchInput from '@/Components/Forms/SearchInput.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import MessageForm from '@/Components/Forms/MessageForm.vue';
import MessageTabs from '@/Components/Custom/MessageTabs.vue';
import ShowFriendMessages from './Partials/ShowFriendMessages.vue';
import ShowFriendList from './Partials/ShowFriendList.vue';

const page = usePage();

const props = defineProps({
    friends: Array,
    pendingFriends: Array,
    pendingInvites: Array,
    messages: Object
});

const filteredMessages = ref(props.messages);

const isMessageModalOpen = ref(false);
const selectedFriend = ref(null);
const isLoading = ref(false);

const form = useForm({
    username: null
});

const submit = () => {
    form.post(route('profile.friends.store', page.props.auth.user), {
        errorBag: 'addFriend',
        preserveScroll: true,
        preserveState: "errors",
        onSuccess: () => form.reset(),
        onError: (err) => console.log(err)
    });
};

const getFriend = (result) => {
    form.username = result.username
};

const selectFriend = (friend) => {
    selectedFriend.value = friend;
}

const openMessageModal = () => {
    if (props.friends.length === 0) {
        return;
    }

    if (!selectedFriend.value) {
        selectedFriend.value = props.friends[0];
    }

    isMessageModalOpen.value = true;
};

const closeMessageModal = (friend) => {
    isMessageModalOpen.value = false;
};

const selectMessage = (message) => {
    if (!message || message.receiver) return;

    router.put(route('profile.friends.messages.update', { user: message.sender ? message.sender : message.receiver, message: message.id }), { read: true },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                filteredMessages.inbox.data.filter(m => m.id === message.id)[0].read = true;
            }
        });
}

const deleteMessage = (message) => {
    router.delete(route('profile.friends.messages.delete', { user: message.sender_id, message: message.id }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            filteredMessages.value.inbox.data.filter(m => m.id !== message.id);
            filteredMessages.value.sent.data.filter(m => m.id !== message.id);
        }
    });
    isMessageModalOpen.value = false;
}

watch(() => props.messages, () => {
    filteredMessages.value = props.messages;
});
</script>

<template>
    <app-layout title="Friends">
        <show-friend-messages :friend="selectedFriend" :messages="filteredMessages">
            <template #message-tabs>
                <message-tabs :isLoading="false" :messages="filteredMessages" @select="selectMessage"
                    @delete="deleteMessage" @reply="openMessageModal">
                    <template #user-search>
                        <form class="flex gap-4">
                            <search-input searchType="users" :value="form.username" @update:model-value="getFriend"
                                class="flex-none w-64" />
                            <div>
                                <primary-button @click="submit">Add</primary-button>
                            </div>
                        </form>
                    </template>
                    <template #friends>
                        <show-friend-list :friends="friends" :pendingFriends="pendingFriends"
                            :pendingInvites="pendingInvites" :selectedFriend="selectedFriend" @select="selectFriend"
                            @send="openMessageModal" />
                    </template>
                </message-tabs>
            </template>
        </show-friend-messages>
    </app-layout>

    <message-form :isOpen="isMessageModalOpen" :friend="selectedFriend" @close="closeMessageModal" />
</template>
