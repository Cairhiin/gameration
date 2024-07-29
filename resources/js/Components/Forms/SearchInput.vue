<script setup>
import { onMounted, ref } from 'vue';
import { debounce } from '@/Utils/index.ts';

const { searchType, multiSelect } = defineProps({
    searchType: String,
    multiSelect: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

const debounceFn = ref(null);
const results = ref([]);
const clickResult = ref(null);
const selected = ref([]);

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

    if (multiSelect) {
        selected.value.push(result)
        clickResult.value = null
        emit('update:modelValue', selected.value)
    } else {
        emit('update:modelValue', result)
    }

    results.value = []
}

const removeFromResults = (index) => {
    selected.value.splice(index, 1);
    clickResult.value = null
    emit('update:modelValue', selected.value);
}
</script>

<template>
    <div v-if="selected && multiSelect">
        <ul class="bg-white flex gap-2">
            <li class="hover:bg-slate-600 hover:cursor-pointer p-2 bg-slate-800 text-slate-100 rounded flex gap-2 items-center"
                v-for="(select, index) in selected" :key="select.id">{{
        select.name }} <i @click="removeFromResults(index)" class="fa-solid fa-xmark"></i></li>
        </ul>
    </div>
    <input type="text" @input="debounceFn($event)" :id="searchType" :name="searchType" :value="clickResult">
    <ul class="bg-white shadow-md rounded-md">
        <li class="hover:bg-gray-100 hover:cursor-pointer p-2" v-for="result in results" :key="result.id"
            @click="setResult(result)">{{
        result.name }}</li>
    </ul>
</template>
