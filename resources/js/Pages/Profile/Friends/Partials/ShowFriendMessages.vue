<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import Pagination from '@/Components/Custom/Pagination.vue';
import Spinner from '@/Components/Custom/Spinner.vue';

const props = defineProps(['friend', 'messages', 'isInbox']);
const page = usePage();

const messages = ref(null);
const tab = ref(0);
const isLoading = ref(false);

const friendId = computed(() => {
    if (props.friend.friend_id === page.props.auth.user.id) {
        return props.friend.pivot.user_id;
    }

    return props.friend.friend_id;
});
</script>

<template>
    <section class="">
        <slot name="message-tabs" />
    </section>
</template>
