<script setup>
import { onMounted, ref } from 'vue';
import { debounce } from '@/Utils/index.ts';

const { searchType } = defineProps({
    searchType: String,
});

const emit = defineEmits(['update:modelValue']);

const debounceFn = ref(null);
const results = ref([]);
const clickResult = ref(null);

onMounted(() => {
    debounceFn.value = debounce((event) => getResults(event), 800)
});

const getResults = (event) => {
    axios.post(route(`${searchType}.search`), { search: event.target.value }).then(response => {
        results.value = response.data;
    });
}

const setResult = (result) => {
    clickResult.value = result.name
    results.value = null
    emit('update:modelValue', result);
}
</script>

<template>
    <input type="text" @input="debounceFn($event)" :id="searchType" :name="searchType" :value="clickResult">
    <ul class="bg-white shadow-md rounded-md">
        <li class="hover:bg-gray-100 hover:cursor-pointer p-2" v-for="result in results" :key="result.id"
            @click="setResult(result)">{{
        result.name }}</li>
    </ul>
</template>
