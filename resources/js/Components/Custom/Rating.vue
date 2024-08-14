<script setup>
import { computed, ref } from 'vue';

const emit = defineEmits(['updateRating']);

const rating = ref(null);
const ratedVal = ref(null);
const hasRated = ref(false);

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
    }
});

const percentage = computed(() => `${(value / 5) * 100}%`);
const color = 'rgb(255, 255, 0)';
const highlight = 'rgb(55, 155, 0)';

const setRating = (evt) => {
    const ratingValue = calculateRating(evt.offsetX, rating.value.clientWidth);
    rating.value.style.setProperty('--color', color);
    rating.value.style.setProperty('--percentage', `${ratingValue}%`);
    ratedVal.value = `${ratingValue}%`;
    hasRated.value = true;
    emit('updateRating', ratingValue);
}

const showRating = (evt) => {
    if (hasRated.value) return;
    const ratingValue = calculateRating(evt.offsetX, rating.value.clientWidth);
    rating.value.style.setProperty('--color', highlight);
    rating.value.style.setProperty('--percentage', `${ratingValue}%`);
}

const resetRatingDisplay = () => {
    if (hasRated.value) return;
    rating.value.style.setProperty('--color', color);
    rating.value.style.setProperty('--percentage', ratedVal.value ?? percentage.value);
}

const calculateRating = (mouseOffset, Width) => Math.round((mouseOffset / Width * 100) / 10) * 10;

</script>

<template>
    <div v-if="rateable" ref="rating" class="gradient rateable" :class="`before:${size}`" :style="{
        '--percentage': percentage, '--color': color
    }" @click.once="setRating" @mousemove="showRating" @mouseleave="resetRatingDisplay">
    </div>
    <div v-else ref="rating" class="gradient" :class="size" :style="{
        '--percentage': percentage, '--color': color
    }"></div>
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
