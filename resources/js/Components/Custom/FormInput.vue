<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input ref="input" class="focus:border-hightlight focus:ring-highlight focus:ring-2 rounded shadow-sm
    bg-dark/50 border-none w-full" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)">
</template>
