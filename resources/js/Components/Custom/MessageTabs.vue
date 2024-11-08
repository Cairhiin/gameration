<script setup>
import { ref, computed } from 'vue';
import Spinner from './Spinner.vue';

const props = defineProps({
    isInbox: Boolean,
    messages: Object,
    isLoading: Boolean
});

const tab = ref(0);
const selectedMessages = computed(() => {
    return tab.value === 0 ? props.messages.inbox : props.messages.sent;
});
</script>
<template>
    <div>
        <aside class="flex gap-4 justify-between mb-4">
            <slot name="user" />
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

        <div :id="tab === 0 ? 'panel-1' : 'panel-2'" role="tabpanel" tabindex="0"
            :aria-labelledby="tab === 0 ? 'tab-1' : 'tab-2'">
            <div class="grid gap-4 grid-cols-8 text-lightVariant uppercase text-xs border-darkVariant py-1 border-b-2">
                <div class="col-span-4">Subject</div>
                <div class="col-span-2" v-if="isInbox">Received</div>
                <div class="col-span-2" v-else>Sent</div>
                <div class="col-span-2" v-if="isInbox">Sender</div>
                <div class="col-span-2" v-else>Receiver</div>
            </div>
            <div v-if="isLoading" class="flex justify-center py-4">
                <spinner :isSpinning="isLoading" />
            </div>
            <template v-else>
                <div v-if="true" v-for="message in selectedMessages" :key="message.id"
                    class="bg-dark border-darkVariant border-b py-1 grid gap-4 grid-cols-8 text-light hover:cursor-pointer hover:bg-highlight/15 text-default"
                    :class="{
                    'font-bold': !message.read, 'text-light/50': message.read
                }" @click="openMessageModal(message)">
                    <div class="col-span-4 truncate">{{ message.subject }}</div>
                    <div class="col-span-2">{{ new Date(message.created_at).toLocaleDateString('fi-FI', {
                    year:
                        'numeric', month: 'numeric', day: 'numeric'
                }) }}</div>
                    <div class="col-span-2 truncate">{{ tab === 0 ? message.sender.username : message.receiver.username
                        }}</div>
                </div>
                <div v-else class="py-1">No messages, select a friend to check
                    their
                    messages.</div>
            </template>
        </div>
    </div>
</template>
