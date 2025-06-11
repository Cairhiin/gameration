<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';

const progressCircle = ref<SVGCircleElement | null>(null);

const { percentage, backgroundColor, progressColor, size, strokeWidth, showPercentage } = defineProps({
    percentage: {
        type: Number,
        default: 0
    },
    backgroundColor: {
        type: String,
        default: '#f0f0f0'
    },
    progressColor: {
        type: String,
        default: '#5394fd'
    },
    size: {
        type: Number,
        default: 250
    },
    strokeWidth: {
        type: Number,
        default: 25
    },
    showPercentage: {
        type: Boolean,
        default: false
    }
});

const radius = computed(() => (size - strokeWidth) / 2);
const halfSize = computed(() => size / 2);
const circumference = computed(() => radius.value * Math.PI * 2);
const dashOffset = computed(() => percentage * circumference.value / 100);

onMounted(() => {
    if (progressCircle) {
        progressCircle.value.style.setProperty('--percentage', percentage.toString());
        progressCircle.value.style.setProperty('--radius', radius.value.toString());
        progressCircle.value.style.setProperty('--circumference', (radius.value * Math.PI * 2).toString());
    }
});
</script>

<template>
    <svg :width="size" :height="size" :viewBox="'0 0 ' + size + ' ' + size" class="circular-progress"
        ref="progressCircle">
        <circle class="bg" :cx="size / 2" :cy="size / 2" :r="(size - strokeWidth) / 2" fill="none"
            :stroke="backgroundColor" :stroke-width="strokeWidth">
        </circle>
        <circle class="fg" :cx="size / 2" :cy="size / 2" :r="(size - strokeWidth) / 2" fill="none"
            transform="rotate(-90)" :stroke="progressColor" :stroke-width="strokeWidth"
            :transform-origin="halfSize + ' ' + halfSize" stroke-linecap="round">
        </circle>
    </svg>
</template>

<style scoped>
.circular-progress {
    --size: 250px;
    --half-size: calc(var(--size) / 2);
    --stroke-width: 25px;
    --circumference: calc(var(--radius) * pi * 2);
    --dash: calc((var(--progress) * var(--circumference)) / 100);
    animation: progress-animation 2s linear 0s 1 forwards;
}

.circular-progress circle.fg {
    stroke-dasharray: var(--dash) calc(var(--circumference) - var(--dash));
}

@property --progress {
    syntax: "<number>";
    inherits: false;
    initial-value: 0;
}

@keyframes progress-animation {
    from {
        --progress: 0;
    }

    to {
        --progress: var(--percentage);
    }
}
</style>
