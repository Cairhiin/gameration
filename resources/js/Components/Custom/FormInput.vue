<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }

    if (input.value.hasAttribute('required')) {
        input.value.setAttribute('aria-required', 'true');
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input type="text" ref="input" class="focus:border-hightlight focus:ring-highlight focus:ring-2 rounded shadow-sm
    bg-darkVariant/50 border-none w-full" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)">
</template>
