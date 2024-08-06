<script setup>
import { computed, ref } from 'vue';

const emit = defineEmits(['updateRating']);

const rating = ref(null);

const { value } = defineProps({
    value: {
        type: Number,
        default: 0,
        max: 5
    }
});
const percentage = computed(() => `${(value / 5) * 100}%`);

const setRating = (evt) => {
    const rateWidth = rating.value.clientWidth;
    const offset = evt.offsetX;
    const percentage = offset / rateWidth * 100;
    const ratingValue = Math.round(percentage / 10) * 10;
    rating.value.style.setProperty('--percentage', `${ratingValue}%`);

    emit('updateRating', ratingValue);
}
</script>

<template>
    <div ref="rating" class="gradient before:text-3xl" :style="{ '--percentage': percentage }" @click.once="setRating">
    </div>
</template>

<style scoped>
.gradient {
    &::before {
        content: '★★★★★';
        letter-spacing: 1px;
        background: linear-gradient(90deg, rgb(255, 255, 0) var(--percentage), #748D92 var(--percentage));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
}
</style>
