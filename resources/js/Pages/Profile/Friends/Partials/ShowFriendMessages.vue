<script setup>
import { ref, computed, watch } from 'vue';
import Pagination from '@/Components/Custom/Pagination.vue';
import ShowMessageModal from '@/Components/Custom/ShowMessageModal.vue';
import axios from 'axios';

const props = defineProps(['friend']);

const messages = ref(null);
const tab = ref(0);
const isMessageModalOpen = ref(false);
const selectedMessage = ref(null);

const selectedMessages = computed(() => {
    return tab.value === 0 ? messages.value?.inbox : messages.value?.sent;
});

watch(() => props.friend, (newValue) => {
    if (Object.keys(newValue).length === 0 && newValue.constructor === Object) return;
    axios.get(route('profile.friends.messages', { user: newValue.friend_id })).then(
        (res) => messages.value = res.data
    );
});

const openMessageModal = (message) => {
    isMessageModalOpen.value = true;
    selectedMessage.value = message;

    // if message is already read or if message.receiver is not null, return
    if (message.receiver || message.read) return;

    // mark message as read
    messages.value.inbox.data.filter(m => m.id === message.id)[0].read = true;
    axios.put(route('profile.friends.messages.update', { user: props.friend.friend_id, message: message.id }), { read: true });
};

const deleteMessage = (message) => {
    axios.delete(route('profile.friends.messages.destroy', { user: props.friend.friend_id, message: message.id }));
}
</script>

<template>
    <section
        class="flex flex-col justify-between gap-4 w-3/4 backdrop-blur-sm bg-dark/70 rounded-lg border border-darkVariant p-8 my-8 min-h-96">
        <div>
            <aside class="flex gap-4 justify-between mb-4">
                <div class="uppercase text-lightVariant">{{ props.friend?.username }}</div>
                <div class="flex gap-4">
                    <button role="tab" aria-selected="true" aria-controls="panel-1" id="tab-1" tabindex="0" :class="{
                    'border-darkVariant border rounded text-lightVariant': tab === 0
                }" class="px-2" @click.prevent="tab = 0">Inbox
                    </button>
                    <button role="tab" aria-selected="false" aria-controls="panel-2" id="tab-2" tabindex="-1" :class="{
                    'border-darkVariant border rounded text-lightVariant': tab === 1
                }" class="px-2" @click.prevent="tab = 1">
                        Sent
                    </button>
                </div>
            </aside>

            <!-- Received -->
            <div v-if="tab === 0" id="panel-1" role="tabpanel" tabindex="0" aria-labelledby="tab-1">
                <div
                    class="grid gap-4 grid-cols-6 text-lightVariant uppercase text-sm border-darkVariant py-1 border-b-2">
                    <div class="col-span-4">Subject</div>
                    <div class="col-span-2">Received</div>
                </div>
                <div v-if="selectedMessages?.data?.length" v-for="message in selectedMessages?.data" :key="message.id"
                    class="bg-dark border-darkVariant border-b py-1 grid gap-4 grid-cols-6 text-light hover:cursor-pointer hover:bg-highlight/15"
                    :class="{
                    'font-bold': !message.read, 'text-light/50': message.read
                }" @click="openMessageModal(message)">
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

            <!-- Sent -->
            <div v-if="tab === 1" id="panel-2" role="tabpanel" tabindex="0" aria-labelledby="tab-2">
                <div
                    class="grid gap-4 grid-cols-6 text-lightVariant uppercase text-sm border-darkVariant py-1 border-b-2">
                    <div class="col-span-4">Subject</div>
                    <div class="col-span-2">Sent</div>
                </div>
                <div v-if="selectedMessages?.data?.length" v-for="message in selectedMessages?.data" :key="message.id"
                    class="bg-dark border-darkVariant border-b py-1 grid gap-4 grid-cols-6 text-light hover:cursor-pointer hover:bg-highlight/15"
                    :class="{ 'font-bold': !message.read }" @click="openMessageModal(message)">
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
        </div>

        <!-- Pagination -->
        <pagination :links="selectedMessages?.links" />

        <!-- Actions -->
        <nav v-if="props.friend?.friend_id" class="flex gap-4 mt-12 max-w-80">
            <slot name="actions" />
        </nav>
    </section>

    <!-- Message Modal -->
    <show-message-modal @close="isMessageModalOpen = false" @delete="deleteMessage" :message="selectedMessage"
        :show="isMessageModalOpen" />
</template>
