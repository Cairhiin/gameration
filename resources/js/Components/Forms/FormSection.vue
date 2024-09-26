<script setup>
import { computed, useSlots } from 'vue';

defineProps({
    title: String
});

const emit = defineEmits(['onSubmit']);

const hasActionButtons = computed(() => !!useSlots().actions);
</script>

<template>
    <section class="max-w-xl mx-auto rounded-xl border border-darkVariant backdrop-blur-sm shadow-dark-sm p-8">
        <h2 class="text-center font-bold uppercase text-xl text-lightVariant mb-6">{{ title }}</h2>
        <form @submit.prevent="emit('onSubmit')">
            <div class="flex flex-col m-8 gap-4">
                <slot name="form" />
            </div>

            <div v-if="hasActionButtons" class="m-8">
                <slot name="actions" />
            </div>
        </form>
    </section>
</template>
