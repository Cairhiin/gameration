<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue';

const emit = defineEmits<
    {
        updateRating: [value: number]
    }>();

const rating = ref<HTMLDivElement>(null);
const ratedVal = ref<string>(null);
const hasRated = ref<boolean>(false);

const { value, rateable, size } = defineProps({
    value: {
        type: Number,
        default: 0,
        max: 5
    },
    rateable: {
        type: Boolean,
        default: true
    },
    size: {
        type: String,
        default: 'text-2xl'
    },
});

const percentage = computed<string>(() => `${(value / 5) * 100}%`);
const color: string = '#42bfdd';
const highlight: string = '#F6AE2D';

onMounted(() => {
    rating.value.style.setProperty('--percentage', `${percentage.value}`);
    console.log('Rating component mounted', rating.value.style.getPropertyValue('--percentage'));
});

const setRating = (evt: MouseEvent): void => {
    const ratingValue = calculateRating(evt.offsetX, rating.value.clientWidth);
    rating.value.style.setProperty('--color', color);
    rating.value.style.setProperty('--percentage', `${ratingValue}%`);
    ratedVal.value = `${ratingValue}%`;
    hasRated.value = true;
    emit('updateRating', ratingValue);
}

const showRating = (evt: MouseEvent): void => {
    if (hasRated.value) return;
    const ratingValue = calculateRating(evt.offsetX, rating.value.clientWidth);
    rating.value.style.setProperty('--color', highlight);
    rating.value.style.setProperty('--percentage', `${ratingValue}%`);
}

const resetRatingDisplay = (): void => {
    if (hasRated.value) return;
    rating.value.style.setProperty('--color', color);
    rating.value.style.setProperty('--percentage', ratedVal.value ?? percentage.value);
}

const calculateRating = (mouseOffset: number, width: number): number => Math.round((mouseOffset / width * 100) / 10) * 10;

</script>

<template>
    <div v-if="rateable" ref="rating" class="gradient rateable" :style="{
        '--percentage': percentage, '--color': color
    }" :class="size" @click.once="setRating" @mousemove="showRating" @mouseleave="resetRatingDisplay">
    </div>
    <div v-else ref="rating" class="gradient" :style="{
        '--percentage': percentage, '--color': color
    }" :class="size"></div>
</template>

<style scoped>
.gradient {
    &::before {
        content: '★★★★★';
        letter-spacing: 1px;
        background: linear-gradient(90deg, var(--color) var(--percentage), #748D92 var(--percentage));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    &:hover.rateable {
        cursor: pointer;
    }
}
</style>
