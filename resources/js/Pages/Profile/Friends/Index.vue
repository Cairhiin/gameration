<script setup>
import { ref } from 'vue';
import { usePage, useForm, router } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import SearchInput from '@/Components/Forms/SearchInput.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import DangerButton from '@/Components/Custom/DangerButton.vue';
import DialogModal from '@/Components/Custom/DialogModal.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import MessageForm from '@/Components/Forms/MessageForm.vue';
import { capitalize } from '@/Utils/index.ts';

const page = usePage();

const messages = ref([]);
const isMessageModalOpen = ref(false);
const selectedFriend = ref({});

const form = useForm({
    username: null
});

const { friends, pendingFriends, pendingInvites } = defineProps({
    friends: Array,
    pendingFriends: Array,
    pendingInvites: Array,
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

const handleInvite = (user, invite) => {
    router.put(route('profile.friends.update', { user: user }), { accepted: invite })
};

const getFriend = (result) => {
    form.username = result.username
};

const loadMessages = (user) => {
    axios.get(route('profile.friends.messages', { user: user })).then(
        (res) => messages.value = res.data
    );
};

const messageUser = (user) => {
    router.get(route('profile.friends.messages.store', { user: user, message: message.value }));
}

const openMessageModal = (user) => {
    selectedFriend.value = user;
    isMessageModalOpen.value = true;
};

const closeMessageModal = () => {
    selectedFriend.value = {};
    isMessageModalOpen.value = false;
}
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
            <section class="w-3/4 backdrop-blur-sm bg-dark/70 rounded-lg border border-darkVariant p-8 my-8
        grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="grid gap-4 grid-cols-3 my-4">
                    <div>Subject</div>
                    <div>Received</div>
                </div>
            </section>

            <section class="w-1/4 backdrop-blur-sm bg-dark/70 rounded-lg border border-darkVariant p-8 my-8">
                <div class="mb-4">
                    <h2>Friends</h2>
                    <ul v-if="friends.length" class="text-sm uppercase text-lightVariant">
                        <li v-for="friend in friends" :key="friend.id" @click="loadMessages(friend.friend_id)"
                            class="flex gap-4 justify-between odd:bg-dark/60 even:bg-darkVariant/60 py-1 items-center">
                            <div>{{ capitalize(friend.username) }}
                            </div>
                            <div class="flex gap-4 justify-end">
                                <primary-button @click="openMessageModal(friend)" size="text-xs"><i
                                        class="fa fa-solid fa-envelope"></i></primary-button>
                                <danger-button @click="deleteUser(friend.user_id)" size="text-xs"><i
                                        class="fa fa-solid fa-xmark"></i></danger-button>
                            </div>
                        </li>
                    </ul>
                    <div v-else class="text-sm uppercase text-lightVariant">No Friends</div>
                </div>

                <div class="mb-4">
                    <h2>Pending Invites</h2>
                    <ul v-if="pendingInvites.length" class="text-sm uppercase text-lightVariant">
                        <li v-for="invite in pendingInvites" :key="invite.friend_id"
                            class="flex gap-4 justify-between odd:bg-dark/60 even:bg-darkVariant/60 py-1 items-center">
                            <div>{{ capitalize(invite.username) }}
                            </div>
                            <div class="flex gap-4 justify-end">
                                <primary-button @click="handleInvite(invite.pivot.user_id, true)" size="text-xs"><i
                                        class="fa fa-solid fa-check"></i></primary-button>
                                <danger-button @click="handleInvite(invite.pivot.user_id, false)" size="text-xs"><i
                                        class="fa fa-solid fa-xmark"></i></danger-button>
                            </div>
                        </li>
                    </ul>
                    <div v-else class="text-sm uppercase text-lightVariant">No Pending Invites</div>
                </div>

                <div class="mb-4">
                    <h2>Pending Friends</h2>
                    <ul v-if="pendingFriends.length" class="text-sm uppercase text-lightVariant">
                        <li v-for="friend in pendingFriends" :key="friend.id"
                            class="flex gap-4 justify-between odd:bg-dark/60 even:bg-darkVariant/60 py-1 items-center">
                            {{ capitalize(friend.username) }}
                        </li>
                    </ul>
                    <div v-else class="text-sm uppercase text-lightVariant">No Pending Friends</div>
                </div>
            </section>
        </div>
    </app-layout>

    <message-form :isOpen="isMessageModalOpen" :friend="selectedFriend" @close="closeMessageModal" />
</template>
