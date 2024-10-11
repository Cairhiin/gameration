<script setup>
import { usePage, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SearchInput from '@/Components/Forms/SearchInput.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import FormSection from '@/Components/Forms/FormSection.vue';
import { capitalize } from '@/Utils/index.ts';

const page = usePage();

const form = useForm({
    username: null
});

const { friends, pendingFriends, pendingInvites } = defineProps({
    friends: Array,
    pendingFriends: Array,
    pendingInvites: Array
});

const submit = () => {
    form.post(route('users.friends.store', page.props.auth.user), {
        errorBag: 'addFriend',
        preserveScroll: true,
        preserveState: "errors.addFriend",
        onSuccess: () => form.reset(),
        onError: (err) => console.log(err)
    });
}

const acceptInvite = (invite) => {
    router.put(route('profile.friends.update', { friend: invite.pivot.user_id }), { invite })
}

const getFriend = (result) => {
    form.username = result.username
}
</script>

<template>
    <app-layout title="Friends">

        <section>
            <div class="mb-4">
                <h2>Friends</h2>
                <ul v-if="friends.length" class="text-sm uppercase text-lightVariant">
                    <li v-for="friend in friends" :key="friend.id">
                        {{ capitalize(friend.username) }}
                    </li>
                </ul>
                <div v-else class="text-sm uppercase text-lightVariant">No Friends</div>
            </div>

            <div class="mb-4">
                <h2>Pending Invites</h2>
                <ul v-if="pendingInvites.length" class="text-sm uppercase text-lightVariant">
                    <li v-for="invite in pendingInvites" :key="invite.friend_id" class="flex gap-4 justify-between">
                        <div>{{ capitalize(invite.username) }}
                        </div>
                        <div class="flex gap-4 justify-end">
                            <primary-button
                                @click=" router.put(route('profile.friends.update', { user: invite.pivot.user_id }), { invite })">Accept</primary-button>
                            <primary-button
                                @click="router.delete(route('profile.friends.delete', { user: invite.pivot.user_id }), { invite })">Decline</primary-button>
                        </div>
                    </li>
                </ul>
                <div v-else class="text-sm uppercase text-lightVariant">No Pending Invites</div>
            </div>

            <div class="mb-4">
                <h2>Pending Friends</h2>
                <ul v-if="pendingFriends.length" class="text-sm uppercase text-lightVariant">
                    <li v-for="friend in pendingFriends" :key="friend.id">
                        {{ capitalize(friend.username) }}
                    </li>
                </ul>
                <div v-else class="text-sm uppercase text-lightVariant">No Pending Friends</div>
            </div>
        </section>

        <form-section title="Friends" @on-submit="submit">
            <template #form>
                <input-label forHtml="publisher">Add Friends</input-label>
                <search-input searchType="users" :value="form.username" @update:model-value="getFriend" />
                <error-message v-if="page.props.errors.addFriend && page.props.errors.addFriend.username">{{
                    page.props.errors.addFriend.username }}</error-message>
            </template>

            <template #actions>
                <primary-button class="mt-4" type="submit">Send Invite</primary-button>
            </template>
        </form-section>
    </app-layout>
</template>
