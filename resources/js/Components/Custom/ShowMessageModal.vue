<script setup>
import DialogModal from '@/Components/Custom/DialogModal.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import DangerButton from '@/Components/Custom/DangerButton.vue';

const props = defineProps({
    message: Object,
    show: {
        type: Boolean,
        default: false,
    }
});

const emit = defineEmits(['close', 'delete']);
</script>

<template>
    <dialog-modal :show="show" @close="emit('close')">
        <template #title>
            <h2 class="font-bold text-xl">{{ message.subject }}</h2>
            <h3 v-if="message.receiver" class="text-sm">
                <span class="text-xs text-light">TO:</span> {{
        message.receiver?.username }}
                <span class="text-xs text-light ml-4">FROM:</span> You
            </h3>
            <h3 v-else class="text-sm"><span class="text-sm text-light">FROM:</span> You <span
                    class="text-sm text-light ml-4">TO:</span>{{
        message.sender?.username
    }} </h3>
        </template>
        <template #content>
            <p class="text-lg">{{ message.body }}</p>
        </template>
        <template #footer>
            <div class="flex justify-end gap-4">
                <primary-button @click="emit('close')" class="w-32">
                    Close
                </primary-button>
                <danger-button @click="emit('delete')" class="w-32">
                    Delete
                </danger-button>
            </div>
        </template>
    </dialog-modal>
</template>
