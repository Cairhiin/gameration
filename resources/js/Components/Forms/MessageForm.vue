<script lang="ts" setup>
import { useForm, usePage } from '@inertiajs/vue3';
import DialogModal from '@/Components/Custom/DialogModal.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import DangerButton from '@/Components/Custom/DangerButton.vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';
import FormInput from '@/Components/Custom/FormInput.vue';
import ErrorMessage from '@/Components/Forms/ErrorMessage.vue';
import type { User } from '@/Types';
import type { PropType } from 'vue';
import type { InertiaPageProps } from '@/Types/inertia';

const { friend, isOpen } = defineProps({
    friend: Object as PropType<User>,
    isOpen: { type: Boolean, default: false }
});

const page = usePage<InertiaPageProps>();

const emit = defineEmits<{
    close: []
}>();

const form = useForm<{ body: string, subject: string }>({
    body: '',
    subject: '',
});

const submit = (user: User): void => {
    form.post(route('profile.friends.messages.store', { user: user }), {
        preserveScroll: true,
        preserveState: "errors",
        onSuccess: (): void => {
            emit('close');
        },
        onError: (): void => {
            form.reset();
        },
    });
}
</script>

<template>
    <dialog-modal :show="isOpen" @close="emit('close')">
        <template #title>
            Send message to {{ friend.username }}
        </template>

        <template #content>
            <form @submit.prevent="submit(friend)" id="messageForm">

                <!-- Subject -->
                <div class="flex flex-col gap-4">
                    <input-label forHtml="message">Subject</input-label>
                    <form-input type="text" name="subject" id="subject" v-model="form.subject" required />
                    <error-message v-if="page.props.errors.addMessage?.subject">{{ page.props.errors.addMessage?.subject
                        }}</error-message>
                </div>

                <!-- Message -->
                <div class="flex flex-col gap-4 mb-4">
                    <input-label forHtml="message">Message</input-label>
                    <form-input type="text" name="body" id="body" v-model="form.body" required />
                    <error-message v-if="page.props.errors.addMessage?.body">{{ page.props.errors.addMessage?.body
                        }}</error-message>
                </div>
            </form>
        </template>

        <template #footer>
            <div class="flex justify-end gap-4">
                <danger-button @click="emit('close')">
                    <div class="flex gap-2 items-center"><i class="fa fa-solid fa-xmark"></i> Cancel</div>
                </danger-button>

                <primary-button form="messageForm" type="submit" :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    <div class="flex gap-2 items-center"><i class="fa fa-solid fa-paper-plane"></i> Send</div>
                </primary-button>
            </div>
        </template>
    </dialog-modal>
</template>
