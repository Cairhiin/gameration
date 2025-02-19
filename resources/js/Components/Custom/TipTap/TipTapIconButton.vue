<script lang="ts" setup>
import { ref, type PropType } from 'vue';

defineProps({
    type: {
        type: String as PropType<'button' | 'submit' | 'reset'>,
        default: 'button',
    },
    icon: {
        type: String,
        default: ''
    },
    tooltipText: {
        type: String,
        default: ''
    },
    buttonText: {
        type: String,
        required: true
    }
});

const emit = defineEmits<{
    click: []
}>();

const showTooltip = ref<boolean>(false);
</script>
<template>
    <button :type="type" @click="emit('click')" @mouseover="showTooltip = true" @mouseleave="showTooltip = false" class="relative h-8 w-8 focus:outline-none focus:ring-2 transition ease-in-out duration-300
         rounded-full focus:ring-dark-highlight-variant/50 flex items-center justify-center" :aria-label="buttonText">
        <i class="fa fa-solid" :class="icon"></i>
        <Transition name="tooltip" enter-from-class="opacity-0" enter-active-class="transition duration-1000">
            <div class="absolute -left-12 px-4 py-2 text-xs uppercase font-bold min-w-32 rounded-full
            bg-dark text-dark-highlight-variant " v-if="showTooltip && tooltipText">
                <div class="relative w-full text-center">{{ tooltipText }}
                    <div class="absolute -top-3 rotate-45 left-1/2 w-2 h-2 bg-darkVariant">
                    </div>
                </div>
            </div>
        </Transition>
    </button>
</template>
