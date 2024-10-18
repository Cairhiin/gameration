<script setup>
import { ref, computed } from 'vue';
import { usePage, useForm, router } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import SearchInput from '@/Components/Forms/SearchInput.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import MessageForm from '@/Components/Forms/MessageForm.vue';
import { capitalize } from '@/Utils/index.ts';

const page = usePage();

const { friends, pendingFriends, pendingInvites } = defineProps({
    friends: Array,
    pendingFriends: Array,
    pendingInvites: Array,
});

const messages = ref([]);
const tab = ref('inbox');
const isMessageModalOpen = ref(false);
const selectedFriend = ref({});

const form = useForm({
    username: null
});

console.log(messages.value);

const selectedMessages = computed(() => {
    return tab === 'inbox' ? messages.value.inbox?.data : messages.value.sent?.data;
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
        (res) =>
            messages.value = res.data
    );
};

const messageUser = (user) => {
    router.get(route('profile.friends.messages.store', { user: user, message: message.value }));
};

const openMessageModal = (user) => {
    selectedFriend.value = user;
    isMessageModalOpen.value = true;
};

const closeMessageModal = () => {
    selectedFriend.value = {};
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
            <section class="w-3/4 backdrop-blur-sm bg-dark/70 rounded-lg border border-darkVariant p-8 my-8">
                <aside class="flex gap-4 justify-end mb-4">
                    <div id="inbox" :class="{ 'border-darkVariant border rounded text-lightVariant': tab === 'inbox' }"
                        class="px-2">Inbox
                    </div>
                    <div id="inbox" :class="{ 'border-darkVariant border rounded text-lightVariant': tab === 'sent' }">
                        Sent</div>
                </aside>
                <div>
                    <div
                        class="grid gap-4 grid-cols-6 text-lightVariant uppercase text-sm border-darkVariant py-1 border-b-2">
                        <div class="col-span-4">Subject</div>
                        <div class="col-span-2">Received</div>
                    </div>
                    <div v-if="selectedMessages?.length" v-for="message in selectedMessages" :key="message.id"
                        class="bg-dark border-darkVariant border-b py-1 grid gap-4 grid-cols-6 text-light"
                        :class="{ 'font-bold': !message.read }">
                        <div class="col-span-4">{{ message.subject }}</div>
                        <div class="col-span-2">{{ new Date(message.created_at).toLocaleDateString('fi-FI', {
                    year:
                        'numeric', month: 'numeric', day: 'numeric'
                }) }}</div>
                    </div>
                    <div v-else class="py-1">No messages, select a friend to check
                        their
                        messages.</div>
                </div>
            </section>

            <!-- Friends -->
            <section class="w-1/4 backdrop-blur-sm bg-dark/70 rounded-lg border border-darkVariant p-8 my-8">
                <div class="mb-4">
                    <h2>Friends</h2>
                    <ul v-if="friends.length" class="text-sm uppercase text-lightVariant">
                        <li v-for="friend in friends" :key="friend.id" @click="loadMessages(friend.friend_id)"
                            class="flex gap-4 justify-between items-center py-1 hover:cursor-pointer">
                            <div>{{ capitalize(friend.username) }}
                            </div>
                            <div class="flex gap-4 justify-end">
                                <span size="text-xs"><i class="fa fa-solid fa-envelope"></i></span>
                                <span @click="deleteUser(friend.user_id)" size="text-xs"><i
                                        class="fa fa-solid fa-xmark"></i></span>
                            </div>
                        </li>
                    </ul>
                    <div v-else class="text-sm uppercase text-lightVariant">No Friends</div>
                </div>

                <div class="mb-4">
                    <h2>Pending Invites</h2>
                    <ul v-if="pendingInvites.length" class="text-sm uppercase text-lightVariant">
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
                    <div v-else class="text-sm uppercase text-lightVariant">No Pending Invites</div>
                </div>

                <div class="mb-4">
                    <h2>Pending Friends</h2>
                    <ul v-if="pendingFriends.length" class="text-sm uppercase text-lightVariant">
                        <li v-for="friend in pendingFriends" :key="friend.id"
                            class="flex gap-4 justify-between items-center">
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
