<script lang="ts" setup>
import { computed, useSlots } from 'vue';

defineProps({
    title: String
});

const emit = defineEmits<{
    onSubmit: []
}>();

const hasActionButtons = computed<boolean>(() => !!useSlots().actions);
</script>

<template>
    <section class="max-w-xl mx-auto rounded-xl bg-dark-box/30 shadow-md p-8 ">
        <h2 class="text-center font-bold uppercase text-xl text-lightVariant mb-6">{{ title }}</h2>
        <form @submit.prevent="emit('onSubmit')">
            <div>
                <slot name="form" />
            </div>

            <div v-if="hasActionButtons" class="m-8">
                <slot name="actions" />
            </div>
        </form>
    </section>
</template>
