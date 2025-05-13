<script lang="ts" setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: [String, Number],
    type: String,
});

defineEmits<{
    'update:modelValue': [value: string | number]
}>();

const input = ref<HTMLInputElement>(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }

    if (input.value.hasAttribute('required')) {
        input.value.setAttribute('aria-required', 'true');
    }
});

defineExpose<{
    focus: () => void
}>({ focus: () => input.value.focus() });
</script>

<template>
    <input :type="type" ref="input" class="focus:border-highlight focus:ring-highlight focus:ring-2 rounded shadow-sm
    bg-dark-box border-none w-full p-2" :value="modelValue"
        @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)">
</template>
