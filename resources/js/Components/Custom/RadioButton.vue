<script setup>
import { computed, toRefs } from 'vue';
import InputLabel from '@/Components/Custom/InputLabel.vue';

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    options: {
        type: Array,
        required: true,
        validator: opts =>
            Array.isArray(opts) &&
            opts.every(opt => typeof opt.value !== 'undefined' && typeof opt.label === 'string')
    },
    modelValue: {
        required: false
    },
    name: {
        type: String,
        default: () => `radio-${Math.random().toString(36).substr(2, 9)}`
    },
    bgColor: {
        type: String,
        default: 'bg-dark-box'
    },
    textColor: {
        type: String,
        default: 'text-light'
    },
    checkedBgColor: {
        type: String,
        default: 'bg-highlight'
    },
    checkedTextColor: {
        type: String,
        default: 'text-dark'
    }
});
const emit = defineEmits(['update:modelValue']);
const { title, options, modelValue, name } = toRefs(props);

const titleId = computed(() => `${name.value}-legend`);
const checkedBgColor = computed(() => `has-checked:${props.checkedBgColor}`);

function onChange(value) {
    emit('update:modelValue', value);
}
</script>

<template>
    <fieldset :aria-labelledby="titleId" role="radiogroup">
        <legend :id="titleId" class="text-sm uppercase font-bold my-2" :class="textColor">{{ title }}</legend>
        <div class="w-full flex gap-8 justify-left items-center h-12 rounded p-1" :class="bgColor">
            <div v-for="(option, idx) in options" :key="option.value"
                class="grow has-checked:rounded px-2 py-1 h-full transition ease-in-out duration-500"
                :class="checkedBgColor">
                <input type="radio" :id="`${name}-${idx}`" :name="name" :value="option.value" tabindex="0"
                    :checked="modelValue === option.value" :aria-checked="modelValue === option.value"
                    @change="onChange(option.value)" class="sr-only" />
                <input-label :for="`${name}-${idx}`" classObject="mt-0 mb-0 text-center cursor-pointer">{{ option.label
                    }}</input-label>
            </div>
        </div>
    </fieldset>
</template>
