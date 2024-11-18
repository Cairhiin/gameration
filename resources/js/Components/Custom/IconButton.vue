<script setup>
import { computed, ref } from 'vue';

const { variant, size, icon, warning } = defineProps({
    type: {
        type: String,
        default: 'button',
    },
    size: {
        type: String,
        default: 'text-sm'
    },
    variant: {
        type: String,
        default: 'normal'
    },
    warning: {
        type: Boolean,
        default: false
    },
    icon: {
        type: String,
        default: ''
    },
    tooltipText: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['click']);

const showTooltip = ref(false);

const styles = computed(() => {

    let styles = size;

    if (variant === 'normal') {
        styles += ' bg-dark-highlight-variant hover:bg-dark text-dark hover:text-dark-highlight-variant rounded-full focus:ring-dark-highlight-variant';
    }

    if (variant === 'invert') {
        styles += ' bg-dark text-dark-highlight-variant hover:text-dark hover:bg-dark-highlight-variant rounded-full focus:ring-dark-highlight-variant';
    }

    if (warning) {
        variant === 'normal' ? styles = 'text-red-600 bg-transparent hover:text-dark hover:bg-red-600 rounded-full' :
            styles = 'text-red-600 bg-dark hover:text-dark hover:bg-red-600 rounded-full';
    }

    size === 'text-sm' || size === 'text-xs' ? styles += ' h-8 w-8' : styles += ' h-10 w-10';

    return styles;
});

const iconStyle = computed(() => {
    return icon + " " + size
});

const tooltipStyle = computed(() => {
    return size === 'text-sm' || size === 'text-xs' ? '-bottom-8' : '-bottom-10';
})
</script>
<template>
    <button :type="type" @click="emit('click')" @mouseover="showTooltip = true" @mouseleave="showTooltip = false"
        class="relative focus:outline-none focus:ring-2  transition ease-in-out duration-300" :class="styles">
        <i class="fa fa-solid" :class="iconStyle"></i>
        <Transition name="tooltip" enter-from-class="opacity-0" enter-active-class="transition duration-1000">
            <div class="absolute -left-12 bg-darkVariant rounded-3xl px-4 py-2 text-lightVariant text-xs uppercase font-bold min-w-32"
                :class="tooltipStyle" v-if="showTooltip && tooltipText">
                <div class="relative w-full text-center">{{ tooltipText }}
                    <div class="absolute -top-3 rotate-45 left-1/2 w-2 h-2 bg-darkVariant">
                    </div>
                </div>
            </div>
        </Transition>
    </button>
</template>
