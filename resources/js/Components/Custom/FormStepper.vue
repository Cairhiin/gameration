<script setup lang="ts">
import { ref, type PropType } from 'vue'

type Step = {
    title: string;
};

const emit = defineEmits<{
    'stepChanged': [value: number]
}>();


const stepWidth = (): string => {
    return `calc(100% / ${steps.length})`;
};

const { steps, activeStep } = defineProps({
    steps: {
        type: Array as PropType<Step[]>,
        required: true
    },
    activeStep: {
        type: Number,
        required: false
    }
});

const goToStep = (index: number): void => {
    emit('stepChanged', index);
};
</script>

<template>
    <div id="form-stepper">
        <ul class="flex gap-2 justify-between">
            <li v-for="(step, index) in steps" :key="index" class="z-0 relative text-center basis-(--my-basis) before:mt-0 before:mb-1 before:mx-auto
                before:w-6 before:h-6 before:rounded before:block before:z-10
                before:text-center before:bg-dark-box before:font-bold
                after:-z-10 after:absolute after:top-[10px] after:bottom-0 after:-left-1/2 after:-right-1/2
                after:h-1 after:bg-dark-box first:after:h-0 last:after:h-0 after:translate-x-1"
                :class="{ 'before:bg-highlight-bright before:text-dark': index === activeStep }"
                @click="goToStep(index)" :style="{ '--my-basis': stepWidth() }">
                {{ step.title }}
            </li>
        </ul>
    </div>
</template>

<style scoped>
#form-stepper {
    counter-reset: step;
}

#form-stepper li:before {
    counter-increment: step;
    content: counter(step);
}
</style>
