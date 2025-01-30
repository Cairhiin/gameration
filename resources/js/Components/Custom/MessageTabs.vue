<script lang="ts" setup>
import { ref, computed, type PropType } from 'vue';
import Pagination from './Pagination.vue';
import type { MessageList, Message, User, Link } from '@/Types';

const { messages, isLoading } = defineProps({
    messages: Object as PropType<MessageList>,
    isLoading: Boolean
});

const emit = defineEmits<{
    select: [message: Message],
    delete: [message: Message],
    reply: [message: Message]
}>();

const tab = ref<number>(0);

const selectedMessages = computed<Message[]>(() => {
    if (tab.value === 0) return messages.inbox.data;
    return messages.sent.data;
});

const selectedMessageLinks = computed<Link[]>(() => {
    if (tab.value === 0) return messages.inbox.links;
    return messages.sent.links;
});

const getFriend = (message: Message): User => {
    if (message.sender) return message.sender;
    if (message.receiver) return message.receiver;
    return null;
};

const highlightedMessage = ref<Message>(selectedMessages.value[0]);

const setHighlightedMessage = (message: Message): void => {
    highlightedMessage.value = message ? message : null;
    emit('select', message);
};
</script>

<template>
    <div>
        <!-- Tabs & Search -->
        <aside class="flex flex-wrap gap-4 justify-between mb-8 items-center">
            <slot name="tabs" />
            <div class="flex gap-4 text-default">
                <button type="button" role="tab" aria-selected="true" aria-controls="panel-1" id="tab-1" tabindex="0"
                    :class="{
                        'bg-dark-highlight-variant text-dark': tab === 0, 'bg-darkVariant/40': tab !== 0
                    }" class="px-4 rounded-3xl"
                    @click.prevent="tab = 0; setHighlightedMessage(messages.inbox.data[0])">Received
                </button>
                <button type="button" role="tab" aria-selected="false" aria-controls="panel-2" id="tab-2" tabindex="-1"
                    :class="{
                        'bg-dark-highlight-variant text-dark': tab === 1, 'bg-darkVariant/40': tab !== 1
                    }" class="px-4  rounded-3xl"
                    @click.prevent="tab = 1; setHighlightedMessage(messages.sent.data[0])">
                    Sent
                </button>
                <button type="button" role="tab" aria-selected="false" aria-controls="panel-3" id="tab-3" tabindex="-1"
                    :class="{
                        'bg-dark-highlight-variant text-dark': tab === 2, 'bg-darkVariant/40': tab !== 2
                    }" class="px-4  rounded-3xl" @click.prevent="tab = 2">
                    Friends
                </button>
            </div>
            <slot name="user-search" />
        </aside>

        <pagination class="my-4 text-left" :links="selectedMessageLinks" position="left" data="messages" />
        <!-- Friend List -->
        <template v-if="tab === 2">
            <slot name="friends" />
        </template>

        <!-- Messages -->
        <div v-else class="flex gap-8 flex-col lg:flex-row lg:items-start">

            <!-- Message List -->
            <div class="lg:basis-1/3" :id="`panel-${tab + 1}`" role="tabpanel" tabindex="0"
                :aria-labelledby="`tab-${tab + 1}`">
                <template v-if="tab === 0 || tab === 1">
                    <div v-if="selectedMessages?.length" v-for="message in selectedMessages" :key="message.id"
                        class="bg-dark-box/40 border-l-8 p-4 cursor-pointer mb-[2px] last:mb-0"
                        @click="setHighlightedMessage(message)" :class="{
                            'border-l-dark-highlight-variant': message.id === highlightedMessage?.id, 'text-light/40': message.read,
                            'border-l-dark-box/40': message.id !== highlightedMessage?.id
                        }">
                        <div class="flex gap-4 items-center">
                            <div class="shrink-0">
                                <img class="w-12 h-12 rounded-full" :src="getFriend(message).profile_photo_url"
                                    alt="avatar" />
                            </div>
                            <div class="flex flex-col w-full">
                                <div class="flex justify-between leading-6">
                                    <div class="font-bold">{{ getFriend(message).username }}</div>
                                    <div>{{ new Date(message.created_at).toLocaleDateString('fi-FI', {
                                        month:
                                            'numeric', day: 'numeric'
                                    }) }}</div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div :class="{ 'font-bold': !message.read }" class="truncate leading-6">{{
                                        message.subject }}
                                    </div>
                                    <div>
                                        <i class="fa-solid fa-check-double"
                                            :class="{ 'text-lightVariant': !message.read, 'text-sky-500': message.read }"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="w-full py-4 place-self-center text-center bg-dark-box/40">You have not {{ `${tab
                        ===
                        0 ?
                        'received' :
                        'sent'}` }}
                        any messages.</div>
                </template>
            </div>

            <!-- Message Content -->
            <div class="bg-dark-box/40 lg:basis-2/3">
                <div v-if="highlightedMessage">
                    <div class="max-h-16 flex justify-between items-center bg-darkVariant/40 p-4 h-96">
                        <h2 class="uppercase text-sm text-lightVariant"><span class="capitalize text-light">{{ tab === 0
                            ?
                            'From' : 'To'
                                }}:</span> {{ tab === 0 ?
                                    highlightedMessage.sender.username :
                                    highlightedMessage.receiver.username
                            }}
                        </h2>
                        <div class="flex items-center text-lightVariant/80" v-if="highlightedMessage.sender">
                            <button @click.prevent="emit('reply', highlightedMessage)"
                                class="rounded-l border-t border-l border-b border-lightVariant/40 px-3 hover:bg-lightVariant hover:text-dark hover:transition-all duration-300">
                                <i class="fa fa-solid fa-reply"></i>
                            </button>
                            <button @click.prevent="emit('delete', highlightedMessage)"
                                class="rounded-r border border-lightVariant/40 px-3 hover:bg-lightVariant hover:text-dark hover:transition-all duration-300">
                                <i class="fa fa-solid fa-trash"></i>
                            </button>
                        </div>
                        <div v-else>
                            <i class="fa fa-solid fa-check-double"
                                :class="{ 'text-sky-500': highlightedMessage.read, 'text-lightVariant': !highlightedMessage.read }"></i>
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
            </div>
        </div>
    </div>
</template>
