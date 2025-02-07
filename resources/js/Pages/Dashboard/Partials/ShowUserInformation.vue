<script lang="ts" setup>
import ListItem from '@/Components/Custom/ListItem.vue';
import type { PropType } from 'vue';
import type { User } from '@/Types';

const { user } = defineProps({
    user: Object as PropType<User>,
});
</script>

<template>
    <div>
        <ul class="flex flex-col gap-2">
            <list-item>
                <template #label>Name</template>
                <template #value>{{ user.name }}</template>
            </list-item>
            <list-item>
                <template #label>Username</template>
                <template #value>{{ user.username }}</template>
            </list-item>
            <list-item>
                <template #label>Email</template>
                <template #value>{{ user.email }}</template>
            </list-item>
            <list-item>
                <template #label>Roles</template>
                <template #value><span v-for="role in user.roles" :key="role.id">{{ role.name }} </span></template>
            </list-item>

            <list-item>
                <template #label>Games Added</template>
                <template #value>{{ user.games_count ?? 0 }}</template>
            </list-item>
            <list-item>
                <template #label>Games Rated</template>
                <template #value>{{ user.ratings_count ?? 0 }}</template>
            </list-item>
        </ul>
    </div>
</template>
