<script setup>
import { ref, computed } from 'vue';
import Spinner from './Spinner.vue';

const props = defineProps({
    isInbox: Boolean,
    messages: Object,
    friends: Object,
    isLoading: Boolean
});

const tab = ref(0);
const selectedMessages = computed(() => {
    return tab.value === 0 ? props.messages.inbox : props.messages.sent;
});
const highlightedMessage = ref(selectedMessages.value[0]);

const setHighlightedMessage = (message) => {
    highlightedMessage.value = message ? message : null;
}
</script>
<template>
    <div>
        <aside class="flex gap-4 justify-between mb-4">
            <slot name="user" />
            <div class="flex gap-4">
                <button role="tab" aria-selected="true" aria-controls="panel-1" id="tab-1" tabindex="0" :class="{
                    'border-darkVariant border rounded text-lightVariant': tab === 0
                }" class="px-2" @click.prevent="tab = 0; setHighlightedMessage(props.messages.inbox[0])">Received
                </button>
                <button role="tab" aria-selected="false" aria-controls="panel-2" id="tab-2" tabindex="-1" :class="{
                    'border-darkVariant border rounded text-lightVariant': tab === 1
                }" class="px-2" @click.prevent="tab = 1; setHighlightedMessage(props.messages.sent[0])">
                    Sent
                </button>
                <button role="tab" aria-selected="false" aria-controls="panel-3" id="tab-3" tabindex="-1" :class="{
                    'border-darkVariant border rounded text-lightVariant': tab === 2
                }" class="px-2" @click.prevent="tab = 2">
                    Friends
                </button>
            </div>
        </aside>

        <div class="lg:grid lg:gap-4 lg:grid-cols-6">
            <div class="bg-dark-box/40 lg:col-span-2 rounded-lg py-2 divide-y divide-lightVariant/25"
                :id="`panel-${tab + 1}`" role="tabpanel" tabindex="0" :aria-labelledby="`tab-${tab + 1}`">
                <template v-if="tab === 0 || tab === 1">
                    <div v-if="selectedMessages?.length" v-for="message in selectedMessages" :key="message.id"
                        class="p-4 cursor-pointer" @click="highlightedMessage = message"
                        :class="{ 'bg-dark-highlight-variant': message.id === highlightedMessage.id }">
                        <div class="flex gap-4 items-center">
                            <div>
                                <img class="w-12 h-12 rounded-full" :src="message.sender.profile_photo_url"
                                    alt="avatar" />
                            </div>
                            <div class="flex flex-col">
                                <div class="flex justify-between leading-6">
                                    <div class="font-bold">{{ tab === 0 ? message.sender.username :
                    message.receiver.username }}</div>
                                    <div>{{ new Date(message.created_at).toLocaleDateString('fi-FI', {
                    month:
                        'numeric', day: 'numeric'
                }) }}</div>
                                </div>
                                <div :class="{ 'font-bold': !message.read }" class="truncate leading-6">{{
                    message.subject }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="p-4 place-self-center my-auto">You have not {{ `${tab === 0 ? 'received' :
                    'sent'}` }}
                        any messages.</div>
                </template>

                <template v-else>
                    <div v-for="friend in friends" :key="friend.id"
                        class="py-1 hover:cursor-pointer hover:text-light/70 uppercase">
                        {{ friend.username }}
                    </div>
                </template>
            </div>
            <div class="bg-dark-box/40 lg:col-span-4 rounded-lg">
                <template v-if="tab === 0 || tab === 1">
                    <div v-if="highlightedMessage">
                        <div class="flex justify-between items-center bg-darkVariant/40 rounded-t-lg p-4">
                            <h2 class="uppercase text-sm">{{ tab === 0 ? highlightedMessage.sender.username :
                    highlightedMessage.receiver.username
                                }}
                            </h2>
                            <div class="flex items-center text-lightVariant/80">
                                <button
                                    class="rounded-l border-t border-l border-b border-lightVariant/40 px-3 hover:bg-lightVariant hover:text-dark hover:transition-all duration-300"><i
                                        class="fa fa-solid fa-reply"></i></button>
                                <button
                                    class="rounded-r border border-lightVariant/40 px-3 hover:bg-lightVariant hover:text-dark hover:transition-all duration-300"><i
                                        class="fa fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="flex justify-between px-4 py-2">
                            <h3 class="font-bold">{{ highlightedMessage.subject }}</h3>
                            <div>{{ new Date(highlightedMessage.created_at).toLocaleDateString('fi-FI', {
                    month:
                                'numeric', day: 'numeric'
                                }) }}</div>
                        </div>
                        <p class="px-4 py-2">{{ highlightedMessage.body }}</p>
                    </div>
                    <div v-else class="p-6 place-self-center my-auto">No message selected.</div>
                </template>
            </div>
        </div>

    </div>
</template>
