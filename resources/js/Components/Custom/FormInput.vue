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
    <input :type="type" ref="input" class="focus:border-hightlight focus:ring-highlight focus:ring-2 rounded shadow-sm
    bg-darkVariant/50 border-none w-full" :value="modelValue"
        @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)">
</template>
